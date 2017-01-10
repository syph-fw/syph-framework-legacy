<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 19/08/2015
 * Time: 16:25
 */

namespace Syph\Core;

use Syph\AppBuilder\AppBuilder;
use Syph\Autoload\ClassLoader;
use Syph\Console\ConsoleApp;
use Syph\Core\Events\KernelBootEvent;
use Syph\Core\Events\KernelEventList;
use Syph\Core\Events\RequestStartEvent;
use Syph\DependencyInjection\Container\Container;
use Syph\Core\Interfaces\SyphKernelInterface;
use Syph\AppBuilder\Interfaces\BuilderInterface;
use Syph\DependencyInjection\Container\OmniContainer;
use Syph\DependencyInjection\ServiceInterface;
use Syph\Exception\ExceptionHandler;
use Syph\Http\Base\Request;
use Syph\Http\Http;
use Syph\Routing\Router;

abstract class Kernel implements SyphKernelInterface,ServiceInterface
{
    protected $apps = array();
    protected $isBooted;
    protected $env;
    protected $start;
    protected $mode;
    protected $http;
    protected $builder;
    protected $accept_configs = [];
    protected $global_configs = [];
    /**
     * @var Container $container
     */
    protected $container;
    protected $dispatcher;
    protected $syphAppDir;
    protected $syphAppLoggDir;

    const VERSION = '0.8';

    public function __construct(Request $request = null)
    {
        $this->start = microtime(true);
        if(null === $request){
            $this->mode = 'CLI';
            $request = Request::create($this->mode);
        }

        if(!$this->isBooted){
            $this->boot($request);
        }

    }

    private function boot(Request $request)
    {
//        $this->env = $env;

        try{
            $this->syphAppDir = $this->getSyphAppDir();

            $this->initClassLoader();
            $this->loadGlobalConfigs();
            $this->initApps();
            $this->initContainer($request);
            $this->initEventDispatcher();
            $this->initFunctions();
            $this->bindContainerApps();

            if (!$this->mode == 'CLI'){
                $this->bindRouterRequest();
            }

            $this->isBooted = true;
        }catch (\Exception $e){
            throw $e;
        }
        $this->dispatcher->dispatch(KernelEventList::KERNEL_BOOTED, new KernelBootEvent($this->container));
    }

    private function initEventDispatcher()
    {
        $this->dispatcher = $this->container->get('event.dispatcher');
        $this->dispatcher->loadContainerListeners();
    }

    private function initFunctions()
    {
        include_once(realpath(dirname(__FILE__)).'/../Helpers/functions.php');
    }

    private function initClassLoader()
    {
        $loader = new ClassLoader();

        $loader->register();

        foreach (new \DirectoryIterator(USER_APPS_DIR) as $fileInfo) {
            if($fileInfo->isDot()) continue;
            if($fileInfo->isFile()) continue;
            $loader->addNamespace($fileInfo->getFilename(), USER_APPS_DIR.DS.$fileInfo->getFilename());
        }

    }

    private function initApps()
    {
        foreach ($this->registerApps() as $app) {
            $name = $app->getName();
            if (isset($this->apps[$name])) {
                throw new \LogicException(sprintf('You trying to register two apps with the same name "%s"', $name));
            }
            $app->buildConfig($this->accept_configs,$this->global_configs);

            $this->apps[$name] = $app;
        }

    }

    private function initContainer(Request $request)
    {

        $this->container = new Container($this);
        $this->container->set($request);
        $this->container->startContainer($this->global_configs['services']);
        $this->container->loadCustomContainer($this->getCustomList());

        $omniContainer = OmniContainer::getInstance();
        $omniContainer->setContainer($this->container);

    }

    public function getNativeCommands(){
        $files = [];
        foreach (new \DirectoryIterator(ConsoleApp::CONSOLE_DIR.DS."Commands".DS."NativeCommands") as $fileInfo) {
            if($fileInfo->isDot()) continue;
            if($fileInfo->isDir()) continue;
            if(preg_match('/Command\.php/',$fileInfo->getFilename()))
                $files[] = $fileInfo->getBasename('.php');
        }

        return $files;
    }

    private function bindContainerApps(){
        foreach ($this->apps as $app) {
            $app->setContainer($this->container);
        }

    }

    private function bindRouterRequest(){
//        $this->container->set($request);
        /**
         * @var Router $router
         */
        $router = $this->container->get('routing.router');

        /**
         * @var Request $request
         */
        $request = $this->container->get('http.request');
        if($request->get->has('path')){
            $request->setAttributes($router->match($request->method,$request->get->get('path')));
        }else{
            $request->setAttributes($router->match('GET','/'));
        }

    }
    private function loadGlobalConfigs()
    {
        $this->loadInternalConfigs();
        $this->loadGlobalAppConfigs();
    }

    private function loadGlobalAppConfigs()
    {
        $globalAppConfig = $this->global_configs;
        $pathGlobalConfigs = sprintf('%s%sglobal',APP_REAL_PATH,DS);
        $extGlobalConfigs = '.php';
        $files = new \DirectoryIterator($pathGlobalConfigs);
        foreach ($files as $file) {
            if($file->isFile() && in_array($file->getBasename($extGlobalConfigs),$this->accept_configs)){
                $config = require_once $pathGlobalConfigs.DS.$file->getFilename();
                $globalAppConfig = array_merge_recursive ($globalAppConfig,$config);
            }
        }
        $this->global_configs = $globalAppConfig;
        $this->setEnv(array_key_exists('environment', $globalAppConfig) ? $globalAppConfig['environment'] : 'dev');
    }
    private function loadInternalConfigs()
    {
        $globalConfig = [];
        $pathGlobalConfigs = realpath(dirname(__FILE__)).'/Config/';
        $extGlobalConfigs = '.php';
        $globalFiles = require_once $pathGlobalConfigs.'configs'.$extGlobalConfigs;
        $this->accept_configs = $globalFiles;
        if(is_array($globalFiles)){
            if(count($globalFiles) > 0){
                foreach ($globalFiles as $globalFile) {
                    $globalFile .= $extGlobalConfigs;
                    if(file_exists($pathGlobalConfigs.$globalFile)) {
                        $config = require_once $pathGlobalConfigs.$globalFile;
                        $globalConfig = array_merge($globalConfig,$config);
                    }
                }
            }
        }
        $this->global_configs = $globalConfig;
    }

//    private function getServiceList(){
//        $list = require_once 'Config/services.php';
//        return $list['services'];
//    }

    private function getCustomList(){
        $pathCustomServices = $this->syphAppDir.'/../global/services.php';
        if(file_exists($pathCustomServices)) {
            $list = require $pathCustomServices;
            return $list['services'];
        }
        return array();
    }

    public function getSyphAppDir(){
        if (null === $this->syphAppDir) {
            $r = new \ReflectionObject($this);
            $this->syphAppDir = str_replace('\\', '/', dirname($r->getFileName()));
        }

        return $this->syphAppDir;
    }

    public function getSyphAppLoggDir()
    {
        if (null === $this->syphAppLoggDir) {
            $p = $this->getSyphAppDir();
            $this->syphAppLoggDir = $p.DS.'..'.DS.'storage'.DS.'logs';
        }
        return $this->syphAppLoggDir;
    }

    public function handleRequest(BuilderInterface $builder = null)
    {

        if($this->isBooted) {
            //$this->loadBuilder($builder)->register($this->env);
        }
        try {
            $this->dispatcher->dispatch(KernelEventList::REQUEST_HANDLE, new RequestStartEvent(
                $this->container->get('http.request'),$this->container->get('routing.router')
            ));
            $response = $this->getHttp()->run($this->container->get('http.request'));
            
            return $response;
        }catch (\Exception $e){
            return $this->handleException($e);
        }
    }

    private function handleException(\Exception $e)
    {
        $handler = new ExceptionHandler();
        $handler->buildResponse($e);
        return $handler->getResponse();
    }

    private function loadBuilder($builder){
        if(!is_null($builder) && $builder instanceof BuilderInterface){
            return $builder;
        }
        return new AppBuilder();
    }

    /**
     * Gets a HTTP from the container.
     *
     * @return Http
     */
    protected function getHttp()
    {
        return $this->container->get('http.core');
    }


    public function getApps(){
        return $this->apps;
    }

    public function getApp($appName){
        return array($this->apps[$appName]);
    }

    public function getName(){
        return 'kernel';
    }

    public function getConfig($name = null)
    {
        if(null === $name)
        {
            return $this->global_configs;
        }

        if (array_key_exists($name,$this->global_configs))
        {
            return $this->global_configs[$name];
        }
        return null;
    }

    private function setEnv($param)
    {
        $this->env = $param;
    }

    public function getEnv()
    {
        return $this->env;
    }


}

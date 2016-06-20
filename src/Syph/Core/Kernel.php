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
use Syph\DependencyInjection\Container\Container;
use Syph\Core\Interfaces\SyphKernelInterface;
use Syph\AppBuilder\Interfaces\BuilderInterface;
use Syph\DependencyInjection\ServiceInterface;
use Syph\Http\Base\Request;
use Syph\Http\Http;
use Syph\Routing\Router;

abstract class Kernel implements SyphKernelInterface,ServiceInterface
{
    protected $apps = array();
    protected $isBooted;
    protected $env;
    protected $mode;
    protected $http;
    protected $builder;
    /**
     * @var Container $container
     */
    protected $container;
    protected $syphAppDir;

    const VERSION = '0.1';

    public function __construct(Request $request = null)
    {

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
        $this->syphAppDir = $this->getSyphAppDir();

        $this->initClassLoader();
        $this->initApps();
        $this->initContainer($request);
        $this->bindContainerApps();
        if (!$this->mode == 'CLI'){
            $this->bindRouterRequest();
        }

        $this->isBooted = true;
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
            $app->buildConfig();
            $this->apps[$name] = $app;
        }

    }

    private function initContainer(Request $request)
    {
        $this->container = new Container($this);
        $this->container->set($request);
        $this->container->startContainer($this->getServiceList());
        $this->container->loadCustomContainer($this->getCustomList());

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
            $request->setAttributes($router->match($request->get->get('path')));
        }else{
            $request->setAttributes($router->match('/'));
        }

    }

    private function getServiceList(){
        $list = require_once 'Config/services.php';
        return $list['services'];
    }

    private function getCustomList(){
        $pathCustomServices = $this->syphAppDir.'/../global/services.php';
        if(file_exists($pathCustomServices)) {
            $list = require_once $pathCustomServices;
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

    public function handleRequest(BuilderInterface $builder = null)
    {

        if($this->isBooted) {
            //$this->loadBuilder($builder)->register($this->env);
        }

        return $this->getHttp()->run($this->container->get('http.request'));
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


    public function getApp($appName){
        return array($this->apps[$appName]);
    }

    public function getName(){
        return 'kernel';
    }

}

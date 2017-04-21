<?php
namespace Syph\Twig;
use Syph\DependencyInjection\Container\Container;
use Syph\DependencyInjection\Container\OmniContainer;

/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 20/04/2017
 * Time: 21:26
 */
class ExtensionProvider
{
    private $extensions = [];
    /**
     * @var Container $container
     */
    private $container;

    public function __construct()
    {
        $this->container = OmniContainer::getInstance()->getContainer();
        $this->loadExtensions();
    }
    public function getExtensions()
    {
        return $this->extensions;
    }

    private function loadExtensions()
    {
        $extension_list = require_once "extensions.php";

        foreach ($extension_list as $extension_name => $extension) {
            if (array_key_exists('class',$extension)){
                $class = $extension['class'];
                $reflect = new \ReflectionClass($class);
                if (array_key_exists('args',$extension)){
                    $args = $this->loadArgsExtensions($extension['args']);
                    $serviceInstance = $reflect->newInstanceArgs($args);
                    $this->extensions[] = $serviceInstance;
                }else{
                    $serviceInstance = $reflect->newInstanceArgs(array());
                    $this->extensions[] = $serviceInstance;
                }
            }
        }
    }

    private function loadArgsExtensions($args)
    {
        $concrete_args = [];
        foreach ($args as $arg) {
            if($this->container->has($arg)){
                $concrete_args[$arg] = $this->container->get($arg);
            }
        }
        return $concrete_args;
    }
}
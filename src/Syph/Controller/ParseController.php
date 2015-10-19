<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 16/10/2015
 * Time: 22:16
 */

namespace Syph\Controller;


use Syph\Core\Kernel;
use Syph\DependencyInjection\ServiceInterface;

class ParseController implements ServiceInterface{


    function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    public function parse($controller){
        $receivedController = $controller;
        if (3 !== count($parts = explode(':', $controller))) {
            throw new \Exception(sprintf('The "%s" controller is not a valid "a:b:c" controller string.', $controller));
        }

        list($app, $controller, $action) = $parts;
        $controller = str_replace('/', '\\', $controller);
        $apps = array();

        try {
            // this throws an exception if there is no such bundle
            $allApps = $this->kernel->getApp($app, false);
        } catch (\Exception $e) {
            $message = sprintf(
                'The "%s" (from the _controller value "%s") does not exist or is not enabled in your kernel!',
                $app,
                $receivedController
            );
            throw new \Exception($message, 0, $e);
        }

        foreach ($allApps as $a) {
            $try = $a->getNamespace().'\\Controller\\'.$controller;
            if (class_exists($try)) {
                return $try.'::'.$action;
            }

            $apps[] = $a->getName();
            $msg = sprintf('The _controller value "%s:%s:%s" maps to a "%s" class, but this class was not found. Create this class or check the spelling of the class and its namespace.', $bundle, $controller, $action, $try);
        }

        if (count($apps) > 1) {
            $msg = sprintf('Unable to find controller "%s:%s" in bundles %s.', $app, $controller, implode(', ', $apps));
        }

        throw new \Exception($msg);
    }

    public function build($controller){
        
    }
    
    public function getName()
    {
        return 'controller.parse';
    }
    
} 
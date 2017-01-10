<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 27/09/2015
 * Time: 10:56
 */

namespace Syph\AppBuilder;


use Syph\AppBuilder\Interfaces\AppInterface;
use Syph\DependencyInjection\Container\SyphContainer;

class App extends SyphContainer implements AppInterface
{
    protected $name;
    protected $extension;
    protected $path;
    protected $db_strategy;
    protected $default_template_engine;
    public static $custom_config;

    public function buildConfig($accept_configs,$global_configs = array())
    {

        foreach (new \DirectoryIterator($this->getPath().DS.'Config/') as $file) {
            if ($file->isFile() && in_array($file->getBasename('.php'),$accept_configs)) {
                $custom_config = include_once($this->getPath().DS.'Config/'.$file->getFilename());
                static::$custom_config = array_merge($global_configs,$custom_config);
            }
        }


    }
    
    public function getName()
    {
        if (null !== $this->name) {
            return $this->name;
        }
        $name = get_class($this);
        $pos = strrpos($name, '\\');

        return $this->name = false === $pos ? $name : substr($name, $pos + 1);
    }

    public function getNamespace()
    {
        $class = get_class($this);

        return substr($class, 0, strrpos($class, '\\'));
    }

    public function getPath()
    {
        if (null === $this->path) {
            $reflected = new \ReflectionObject($this);
            $this->path = dirname($reflected->getFileName());
        }

        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getDbStrategy()
    {
        return $this->db_strategy;
    }

    /**
     * @return mixed
     */
    public function getDefaultTemplateEngine()
    {
        return $this->default_template_engine;
    }
    
    
}
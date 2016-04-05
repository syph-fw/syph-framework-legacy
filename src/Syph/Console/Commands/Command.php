<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 18/03/2016
 * Time: 16:04
 */

namespace Syph\Console\Commands;


class Command implements CommandInterface
{
    private $name;
    private $application;
    private $aliases = array();
    private $definition;
    private $description;
    private $code;

    const _namespace = __NAMESPACE__;

    /**
     * Command constructor.
     * @param $name
     */
    public function __construct($name,$code)
    {
        $this->name = $name;
        $this->setCode($code);
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @param mixed $application
     */
    public function setApplication($application)
    {
        $this->application = $application;
    }


    /**
     * @return array
     */
    public function getAliases()
    {
        return $this->aliases;
    }

    /**
     * @param array $aliases
     */
    public function setAliases($aliases)
    {
        $this->aliases = $aliases;
    }

    /**
     * @return mixed
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * @param mixed $definition
     */
    public function setDefinition($definition)
    {
        $this->definition = $definition;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param $code
     * @return bool
     */
    public function setCode($code)
    {
        if(is_callable($code)) {
            $this->code = $code;
            return true;
        }

        return false;
    }

    /**
     *
     */
    public function run()
    {
        if(isset($this->code)){
            call_user_func($this->code,[]);
        }

    }
}
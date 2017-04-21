<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 10/10/2015
 * Time: 14:04
 */

namespace Syph\Routing;


use Syph\Security\Firewall\Firewall;
use Syph\Security\Firewall\Gate;
use Syph\Security\Firewall\GateGuardian;

class Route {
    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $pattern
     */
    private $pattern;
    /**
     * @var string $origPattern
     */
    private $origPattern;

    /**
     * @var array $params
     */
    private $params = [];
    /**
     * @var array $params
     */
    private $filledParams = [];
    /**
     * @var callable $callback
     */
    private $callback;
    /**
     * @var bool $stringReference
     */
    private $stringReference = false;
    /**
     * @var string $stringController
     */
    private $stringController;

    private $requestType;
    /**
     * @var RouteGroup $group
     */
    private $group;
    /**
     * @var Gate $gate
     */
    private $gate;

    public function __construct($pattern = null,$callback = null)
    {
        $this->pattern = $pattern;
        $this->callback = $callback;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Route
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }


    /**
     * @return string
     */
    public function getPattern()
    {
        if($this->hasGroup()){

            return $this->group->getPattern().$this->pattern;
        }
        return $this->pattern;
    }

    /**
     * @param string $pattern
     * @return Route
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrigPattern()
    {
        return $this->origPattern;
    }

    /**
     * @param string $origPattern
     * @return Route
     */
    public function setOrigPattern($origPattern)
    {
        $this->origPattern = $origPattern;
        return $this;
    }


    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     * @return Route
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return array
     */
    public function getFilledParams()
    {
        return $this->filledParams;
    }

    /**
     * @param array $filledParams
     * @return Route
     */
    public function setFilledParams($filledParams)
    {
        $this->filledParams = $filledParams;
        return $this;
    }



    /**
     * @return callable
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param callable $callback
     * @return Route
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isStringReference()
    {
        return $this->stringReference;
    }

    /**
     * @param boolean $stringReference
     * @return Route
     */
    public function setStringReference($stringReference)
    {
        $this->stringReference = $stringReference;
        return $this;
    }

    /**
     * @return string
     */
    public function getStringController()
    {
        return $this->stringController;
    }

    /**
     * @param string $stringController
     * @return Route
     */
    public function setStringController($stringController)
    {
        $this->stringController = $stringController;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequestType()
    {
        return $this->requestType;
    }

    /**
     * @param mixed $requestType
     * @return Route
     */
    public function setRequestType($requestType)
    {
        $this->requestType = $requestType;
        return $this;
    }

    public function fillParams($args)
    {
        $params = [];

        for ($i = 0;$i < count($this->getParams());$i++){
            $params[$this->getParams()[$i]] = $args[$i];
        }

        $this->filledParams = $params;
    }

    public function hasGroup()
    {
        return !is_null($this->group);
    }

    /**
     * @return RouteGroup
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param RouteGroup $group
     * @return Route
     */
    public function setGroup(RouteGroup $group)
    {
        $this->group = $group;
        $group->addRoute($this);
        return $this;
    }

    public function setGate(Gate $gate)
    {
        $this->gate = $gate;
        return $this;
    }

    public function getGate()
    {
        return $this->gate;
    }

    public function itsBehindTheGate()
    {
        return $this->gate instanceof Gate;
    }

}
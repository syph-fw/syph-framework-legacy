<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 10/10/2015
 * Time: 14:04
 */

namespace Syph\Routing;


class RouteGroup {
    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $pattern
     */
    private $pattern;

    /**
     * @var array|RouteGroup $groups
     */
    private $group;

    /**
     * @var array $params
     */
    private $routes = [];

    /**
     * @var callable $callback
     */
    private $callback;

    /**
     * RouteGroup constructor.
     * @param string $pattern
     */
    public function __construct($pattern = null)
    {
        $this->pattern = $pattern;
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
     * @return RouteGroup
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
     * @return RouteGroup
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param array $routes
     * @return RouteGroup
     */
    public function setRoutes($routes)
    {
        $this->routes = $routes;
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
     * @return RouteGroup
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
        return $this;
    }

    public function addRoute(Route $route)
    {
        $this->routes[$route->getName()] = $route;
    }

    /**
     * @return array|RouteGroup
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param array|RouteGroup $group
     * @return RouteGroup
     */
    public function setGroup($group)
    {
        $this->group = $group;
        return $this;
    }

    public function hasGroup()
    {
        return !is_null($this->group);
    }

}
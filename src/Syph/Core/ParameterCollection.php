<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 16/01/2017
 * Time: 19:08
 */

namespace Syph\Core;


class ParameterCollection
{
    private $parameters = array();

    /**
     * ParameterCollection constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->set($parameters);
    }

    public function has($key)
    {
        return array_key_exists($key, $this->parameters);
    }

    public function add($key,$value)
    {
        $this->parameters[$key] = $value;
        return $this;
    }

    public function get($key = null)
    {
        return $this->has($key) && !is_null($key)? $this->parameters[$key] : $key;
    }

    public function set(array $parameters)
    {
        foreach ($parameters as $key=>$parameter) {
            $this->add($key, $parameter);
        }
    }

}
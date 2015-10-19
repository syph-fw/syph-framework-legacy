<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 10/10/2015
 * Time: 14:04
 */

namespace Syph\Routing;


class Route {

    private $pattern;
    private $callback;

    public function __construct($pattern,$callback)
    {
        $this->pattern = $pattern;
        $this->callback = $callback;
    }

    /**
     * @return mixed
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @return mixed
     */
    public function getPattern()
    {
        return $this->pattern;
    }



}
<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 08/10/2015
 * Time: 22:20
 */
namespace Syph\Http\Base\HttpVerbose;

class HttpVerbose {

    public $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function getAll(){
        return $this->params;
    }

    public function get($id){
        return $this->params[$id];
    }

    public function set($id,$attr){
        $this->params[$id] = $attr;
    }

    public function has($id)
    {
        return array_key_exists($id, $this->params);
    }

} 
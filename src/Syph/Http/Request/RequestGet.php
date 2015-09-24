<?php
namespace Syph\Http\Request;
/**
 * Created by PhpStorm.
 * User: Bruno, Daniel
 * Date: 13/08/2015
 * Time: 13:43
 */
use Syph\Http\HttpRequest;
class RequestGet extends HttpRequest
{
    public function __get($atrib){
        return $this->$atrib;
    }

    public function __set($atrib, $value){
        $this->$atrib = $value;
    }

}
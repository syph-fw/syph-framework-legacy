<?php
/**
 * Created by PhpStorm.
 * User: prapa
 * Date: 04/04/2016
 * Time: 12:59
 */
namespace Syph\Http\Session\Handler;

class PHPSessionHandler extends \SessionHandler
{
    public function setId($id){
        return session_id($id);
    }

    public function getId(){
        return session_id();
    }

    public function setName($name){
        return session_name($name);
    }

    public function getName(){
        return session_name();
    }
}
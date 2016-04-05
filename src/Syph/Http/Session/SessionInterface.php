<?php
/**
 * Created by PhpStorm.
 * User: prapa
 * Date: 04/04/2016
 * Time: 11:40
 */

namespace Syph\Http\Session;


interface SessionInterface
{
    /* SETUP  */
    public function getId();
    public function setId($id);
    public function getSessionName();
    public function setSessionName($name);

    /* ATTRIBUTES */
    public function set($name,$value);
    public function get($name);
    public function has($name);
    public function getAll();
    public function remove($name);

    public function clear();
    public function register($name);
    public function save();

}
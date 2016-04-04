<?php
/**
 * Created by PhpStorm.
 * User: prapa
 * Date: 04/04/2016
 * Time: 12:16
 */

namespace Syph\Http\Session\Storage;


interface SessionStorageInterface
{
    public function load();
    public function start();
    public function save();
    public function isStarted();
    public function registerBottle(SessionBottle $bottle);

    public function clear();

    public function getId();
    public function setId($id);
    public function getName();
    public function setName($name);

    public function getBottle($name);
}
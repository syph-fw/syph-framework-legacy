<?php

namespace Syph\DB\Interfaces;

interface ConnParametersInterface
{
    public function getHost();
    public function setHost($host);
    public function setPass($pass);
    public function getPass();
    public function setDB($db);
    public function getDB();
    public function setUser($user);
    public function getUser();
    public function setName($name);
    public function getName();

}
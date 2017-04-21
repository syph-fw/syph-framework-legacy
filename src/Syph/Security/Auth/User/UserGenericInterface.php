<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 07/10/2016
 * Time: 23:40
 */

namespace Syph\Security\Auth\User;


interface UserGenericInterface
{
    public function isEnabled();

    public function getUsername();
    public function setUsername($username);
    public function getPassword();
    public function setPassword($pass);
}
<?php
/**
 * Created by PhpStorm.
 * User: PSBI
 * Date: 24/08/2015
 * Time: 14:07
 */

namespace Syph\Controller;


use Syph\DependencyInjection\Container\SyphContainer;

class BaseController extends SyphContainer
{
    public function get($id){
        if('http.session' == $id){
            $session = $this->container->get($id);
            $session->start();
            return $session;
        }
        return $this->container->get($id);
    }
}
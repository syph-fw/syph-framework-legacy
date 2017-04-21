<?php
use \Syph\DependencyInjection\Container\OmniContainer;
$container = OmniContainer::getInstance()->getContainer();

define('BASE_URL',$container->get('http.request')->getBaseUrl());

if ( ! function_exists('sd'))
{
    /**
     * sd = (s)mart (d)ump
     * Dump the passed variables and end the script.
     *
     * @param  mixed
     * @return void
     */
    function sd()
    {
        d(func_get_args()); die;
    }
}


if ( ! function_exists('d'))
{
    /**
     * sd = (s)mart (d)ump
     * Dump the passed variables and end the script.
     *
     * @param  mixed
     * @return void
     */
    function d()
    {
        array_map(function($x) {
            echo "<pre class='smart-dump'>";
            var_dump($x);
        }, func_get_args());
    }
}


if(defined('BASE_URL')){
    if ( ! function_exists('asset'))
    {
        function asset($url){
            return BASE_URL.$url;
        }
    }
    if ( ! function_exists('path'))
    {
        function path($url){
            return BASE_URL.$url;
        }
    }
}


if(function_exists('csrf')){
    function csrf(){
        /**
         * @var \Syph\Security\Csrf\Csrf $csrf
         */
        $csrf = OmniContainer::getInstance()->getContainer()->get('security.csrf');
        return $csrf->getToken();
    }
}
<?php
namespace Syph\Http;
/**
 * Created by PhpStorm.
 * User: PSBI
 * Date: 12/08/2015
 * Time: 17:53
 */
class Redirect
{

    /**
     * Redirect constructor.
     */
    private function __construct(){}
    private function __clone(){}

    public static function to($url){
        header('Location: '.$url);
        exit;
    }
}
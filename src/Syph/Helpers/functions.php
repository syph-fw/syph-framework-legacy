<?php

if ( ! function_exists('dd'))
{
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed
     * @return void
     */
    function dd()
    {
        array_map(function($x) { var_dump($x); }, func_get_args()); die;
    }
}

if ( ! function_exists('asset'))
{

    function asset($url){
        return BASE_URL.$url;
    }
}
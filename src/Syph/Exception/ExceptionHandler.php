<?php
/**
 * Created by PhpStorm.
 * User: prapa
 * Date: 05/09/2016
 * Time: 10:54
 */

namespace Syph\Exception;


use Syph\Http\Response\Response;

class ExceptionHandler
{
    private $response;

    /**
     * ExceptionHandler constructor.
     */
    public function __construct()
    {
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function buildResponse(\Exception $e)
    {
        $generator = new ExceptionResponseGenerator($e);
        $this->response =  new Response($generator->build($e));

    }

}
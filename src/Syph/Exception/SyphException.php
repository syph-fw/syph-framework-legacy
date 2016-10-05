<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 04/10/2016
 * Time: 17:41
 */

namespace Syph\Exception;


use Syph\Http\Response\Response;

class SyphException implements SyphExceptionInterface
{

    private $exception;
    /**
     * @var Response $exceptionResponse
     */
    private $exceptionResponse;
    private $exceptionHandler;

    /**
     * SyphException constructor.
     * @param $exception
     */
    public function __construct($exception = null)
    {
        if(!is_null($exception)){
            $this->setException($exception);
        }
    }

    private function bindException(\Exception $e){
        $this->exceptionHandler = new ExceptionHandler();
        $this->exceptionHandler->buildResponse($e);
        $this->exceptionResponse = $this->exceptionHandler->getResponse();
    }

    /**
     * @return mixed
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @param mixed $exception
     */
    public function setException(\Exception $exception)
    {
        $this->exception = $exception;
        $this->bindException($exception);
    }


    public function getSyphMessage()
    {
        $this->exceptionResponse->show();
    }


}
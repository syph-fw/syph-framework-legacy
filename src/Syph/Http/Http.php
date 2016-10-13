<?php
namespace Syph\Http;
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 12/08/2015
 * Time: 12:16
 * Description: Classe responsavel por tratar as requisi��es
 */
use Syph\Controller\SolveController;
use Syph\DependencyInjection\ServiceInterface;
use Syph\Http\Base\Request;
use Syph\Http\Interfaces\HttpInterface;
use Syph\Http\Base\HttpVerbose\RequestGet;
use Syph\Http\Base\HttpVerbose\RequestPost;
use Syph\Http\Response\Response;

class Http implements HttpInterface, ServiceInterface
{
    protected $solveController;

    private $path;

    private $hasGet;

    private $hasPost;

    private static $get;

    private static $post;

    /**
     * Http constructor.
     */
    public function __construct(SolveController $solveController)
    {
        $this->path = (isset($_GET['path']))?$_GET['path']:"";
        //$this->handleVerboses();
        $this->solveController = $solveController;
    }

    public function getURI()
    {
        return $this->path;
    }

    public function run(Request $request){

        if (false === $controller = $this->solveController->getController($request)) {
            throw new \Exception(sprintf('Unable to find the controller for path "%s". The route is wrongly configured.', $request->getPathInfo()));
        }
        

        $arguments = $this->solveController->getArgs($request, $controller);


        $response = call_user_func_array($controller, $arguments);
        
        return new Response($response);
    }

    public function getRequest(){
        return self::$post;
    }


    public static function getHttpRequest()
    {
        return self::$post;
    }

    public function getName()
    {
        return 'http.core';
    }
}
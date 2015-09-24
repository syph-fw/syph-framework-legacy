<?php
namespace Syph\Http;
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 12/08/2015
 * Time: 12:16
 * Description: Classe responsavel por tratar as requisições
 */
use Syph\Http\Interfaces\HttpInterface;
use Syph\Http\Request\RequestGet;
use Syph\Http\Request\RequestPost;

class Http implements HttpInterface
{
    private $path;

    private $hasGet;

    private $hasPost;

    private static $get;

    private static $post;

    /**
     * Http constructor.
     */
    public function __construct()
    {
        $this->path = (isset($_GET['path']))?$_GET['path']:"";
        $this->handleVerboses();
    }

    public function getRequest()
    {
        return $this->path;
    }

    private function handleVerboses()
    {
        if(count($_GET)>0){
            $this->handleGet();
            $this->hasGet = true;
        }
        if(count($_POST)>0){
            $this->handlePost();
            $this->hasPost = true;
        }
    }

    private function handleGet()
    {
        self::$get = new RequestGet();
        foreach($_GET as $g=>$get){
            self::$get->$g = $get;
        }

    }

    private function handlePost()
    {
        self::$post = new RequestPost();
        foreach($_POST as $p=>$post){
            self::$post->$p = $post;
        }

    }

    public static function getHttpRequest()
    {
        return self::$post;
    }

}
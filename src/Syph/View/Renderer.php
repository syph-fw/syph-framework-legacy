<?php
/**
 * Created by PhpStorm.
 * User: PSBI
 * Date: 20/08/2015
 * Time: 09:59
 */

namespace Syph\View;


use Syph\Twig\Extension\AssetsExtension;
use Syph\View\Interfaces\RendererInterface;

class Renderer implements RendererInterface
{
    private $template;
    private $file;
    private $view_request;
    private $extenssion;
    private $path;
    private $view_path;

    public function __construct($file)
    {
        $this->template = $file;
        $this->extractFileInfo($file);
    }

    public function loadContent($filename,$vars)
    {
        extract($vars);
        include($filename);
    }

    public function render($file,$vars)
    {
        //var_dump($this);die;
        switch($this->extenssion){
            case 'twig':
                $loader = new \Twig_Loader_Filesystem($this->view_path);
                $twig = new \Twig_Environment($loader,array('debug' => true,));
                $twig->addExtension(new \Twig_Extension_Debug());
                $twig->addExtension(new AssetsExtension());
                $template = $twig->loadTemplate($this->view_request);
                return $template->render($vars);
                break;
            case 'php':
                ob_start();
                $this->createFileRender($file,$vars);
                return ob_get_clean();
                break;
        }


    }

    public function createFileRender($file,$vars)
    {
        $filename = $file;
        if($this->validatePath($filename)){
            $this->loadContent($filename,$vars);
        }
    }

    public function validatePath($filename)
    {
        return file_exists($filename);
    }

    public function getFilename()
    {
        return $this->path.$this->file;
    }

    private function extractFileInfo($file)
    {
        $template = explode(':',$file);
        $this->extenssion = substr(strrchr($template[1],'.'),1);
        $this->view_request = $template[1];
        $this->file = substr(strrchr($template[1],'/'),1);
        $this->path = '../app'.DS.$template[0].DS.'View'.DS.substr($template[1], 0,strrpos($template[1], '/')).DS;
        $this->view_path = '../app'.DS.$template[0].DS.'View'.DS;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: PSBI
 * Date: 20/08/2015
 * Time: 09:59
 */

namespace Syph\View;

use Syph\DependencyInjection\ServiceInterface;
use Syph\Http\Base\Request;
use Syph\Helpers\FilesHelper;
use Syph\Twig\Extension\AssetsExtension;
use Syph\Twig\ExtensionProvider;
use Syph\View\Interfaces\RendererInterface;

class Renderer implements RendererInterface,ServiceInterface
{
    private $basePath;
    private $baseUrl;
    private $template;
    private $file;
    private $view_request;
    private $extenssion;
    private $path;
    private $view_path;

    public function __construct(Request $request)
    {
        $this->basePath = str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']);
        $this->baseUrl = $request->getBaseUrl();
    }

    public function run($file)
    {
        $this->template = $file;
        $this->extractFileInfo($file);
        return $this;
    }

    public function loadContent($filename,$vars)
    {
        extract($vars);
        include(FilesHelper::normalizePath($filename));
    }

    public function render($file,$vars)
    {
        switch($this->extenssion){
            case 'twig':
                $loader = new \Twig_Loader_Filesystem(realpath($this->view_path));
                $twig = new \Twig_Environment($loader,array('debug' => true,));
                $twig->addExtension(new \Twig_Extension_Debug());

                $extensionProvider = new ExtensionProvider();

                $extensions = $extensionProvider->getExtensions();
                foreach ($extensions as $extension) {
                    $twig->addExtension($extension);
                }

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

        if($this->validatePath($file)){
            $this->loadContent($file,$vars);
        }
    }

    public function validatePath($filename)
    {
        return file_exists($filename);
    }

    public function getFilename()
    {
        return FilesHelper::normalizePath($this->path.$this->file);
    }

    private function extractFileInfo($file)
    {

        $template = explode(':',$file);
        $this->extenssion = substr(strrchr($template[1],'.'),1);
        $this->view_request = $template[1];
        $this->file = strrchr($template[1],'/') === FALSE ? $template[1] :substr(strrchr($template[1],'/'),1);
        $this->path = '..'.DS.'app'.DS.$template[0].DS.'View'.DS.substr($template[1], 0,strrpos($template[1], '/')).DS;
        $this->view_path = '..'.DS.'app'.DS.$template[0].DS.'View'.DS;

    }

    public function getName()
    {
        return 'view.renderer';
    }
}
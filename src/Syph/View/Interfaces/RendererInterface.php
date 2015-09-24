<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 20/08/2015
 * Time: 09:48
 */

namespace Syph\View\Interfaces;


interface RendererInterface
{
    public function loadContent($filename,$vars);
    public function render($file,$vars);
    public function createFileRender($file,$vars);
    public function validatePath($path);
    public function getFilename();
}
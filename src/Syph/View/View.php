<?php

namespace Syph\View;

use Syph\View\Interfaces\RendererInterface;

/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 12/08/2015
 * Time: 16:10
 */
class View
{
    public static function render(RendererInterface $renderer, $vars = array())
    {
        return $renderer->render($renderer->getFilename(),$vars);
    }

}
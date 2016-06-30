<?php
namespace Syph\Http\Response;
/**
 * Created by PhpStorm.
 * User: prapa
 * Date: 30/06/2016
 * Time: 10:07
 */
class Response
{
    private $content;

    /**
     * Response constructor.
     * @param $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    public function show()
    {
        echo $this->content;
    }


}
<?php
/**
 * Created by PhpStorm.
 * User: prapa
 * Date: 05/09/2016
 * Time: 10:54
 */

namespace Syph\Exception;


class ExceptionResponseGenerator
{

    public function build(\Exception $e)
    {
        return $this->getExceptionPage($e->getMessage());
    }

    public function getHeader()
    {

    }

    public function getBody($message)
    {

    }

    private function getExceptionPage($body)
    {
        return <<<EOF
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf8" />
        <link href="http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic" rel="stylesheet" type="text/css">
        <style>
            h1,p{
                font-family: 'lato', sans-serif;
                text-align:center;
            }
            .container{
                width:960px;
                margin:auto;
                padding-top:2em;
            }
            
            .error-icon{
                width:100%;
                text-align:center;
            }
            
            .error-icon img{
                width:20%;
            }
            .title-exception{
                color: #e25454;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="error-icon">
                <img src="/assets/images/logo_bug.png"/>
            </div>
            <div>
                <h1 class="title-exception">Exception!</h1>
                <p>$body</p>
            </div>
        </div>
    </body>
</html>
EOF;

    }
}
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
    </head>
    <body>
        $body
    </body>
</html>
EOF;

    }
}
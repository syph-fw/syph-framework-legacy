<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 16/01/2017
 * Time: 21:26
 */

namespace Syph\Http\Response;


class JsonResponse extends Response
{

    const OPT_ENCODING = 15;

    public function __construct($content,$code = 200)
    {
        parent::__construct('');

        if(!is_null($content)){
            $content = array_merge(['code'=>$code],$content);
            $content = $this->handleContent($content);
        }else{
            $content = array('code'=>$code);
        }

        $this->setContent($content);
    }

    private function handleContent($content)
    {
        try {

            if (PHP_VERSION_ID < 50400) {
                $content = @json_encode($content, self::OPT_ENCODING);
            } else {
                $content = json_encode($content, self::OPT_ENCODING);
            }

        } catch (\Exception $e) {

            if (PHP_VERSION_ID >= 50400 && 'Exception' === get_class($e) && 0 === strpos($e->getMessage(), 'Failed calling ')) {
                throw $e->getPrevious() ?: $e;
            }

            throw $e;
        }
        return $content;
    }

    public function __toString()
    {
        return $this->getContent();
    }
}
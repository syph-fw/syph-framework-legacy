<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 05/10/2015
 * Time: 23:44
 */

namespace Syph\Http\Base;


use Syph\DependencyInjection\ServiceInterface;
use Syph\Http\Base\HttpVerbose\Header;
use Syph\Http\Base\HttpVerbose\HttpVerbose;
use Syph\Http\Base\HttpVerbose\Server;

class Request implements ServiceInterface{
    const SERVICE_NAME = 'http.request';

    protected static $factory;

    /**
     * Request body parameters ($_POST).
     *
     * @var \Syph\Http\Base\HttpVerbose\HttpVerbose
     *
     * @api
     */
    public $attributes;

    /**
     * Request body parameters ($_POST).
     *
     * @var \Syph\Http\Base\HttpVerbose\HttpVerbose
     *
     * @api
     */
    public $post;

    /**
     * Query string parameters ($_GET).
     *
     * @var \Syph\Http\Base\HttpVerbose\HttpVerbose
     *
     * @api
     */
    public $get;

    /**
     * Server and execution environment parameters ($_SERVER).
     *
     * @var \Syph\Http\Base\HttpVerbose\Server
     *
     * @api
     */
    public $server;

    /**
     * Uploaded files ($_FILES).
     *
     * @var \Syph\Http\Base\HttpVerbose\HttpVerbose
     *
     * @api
     */
    public $files;

    /**
     * Cookies ($_COOKIE).
     *
     * @var \Syph\Http\Base\HttpVerbose\HttpVerbose
     *
     * @api
     */
    public $cookies;

    /**
     * Headers (taken from the $_SERVER).
     *
     * @var \Syph\Http\Base\HttpVerbose\Header
     *
     * @api
     */
    public $headers;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $requestUri;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var string
     */
    public $method;

    /**
     * @var object
     */
    protected $session;


    /**
     * @var string
     */
    protected $defaultLocale = 'en';

    public function __construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        $this->init($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    public function init(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        $this->post = new HttpVerbose($request);
        $this->get = new HttpVerbose($query);
        $this->attributes = new HttpVerbose($attributes);
        $this->cookies = new HttpVerbose($cookies);
        $this->files = new HttpVerbose($files);

        $this->server = new Server($server);
        $this->headers = new Header($this->server->getHeaders());

        $this->content = $content;
        $this->requestUri = null;
        $this->baseUrl = null;
        $this->basePath = null;
        if (PHP_SAPI != 'cli') {
            $this->method = $server['REQUEST_METHOD'];
        }
    }

    /**
     * @return Request A new request
     *
     * @api
     */
    public static function create($mode = 'WEB')
    {
        $server = $_SERVER;

        if ('cli-server' === php_sapi_name() && $mode == 'CLI') {
            if (array_key_exists('HTTP_CONTENT_LENGTH', $_SERVER)) {
                $server['CONTENT_LENGTH'] = $_SERVER['HTTP_CONTENT_LENGTH'];
            }
            if (array_key_exists('HTTP_CONTENT_TYPE', $_SERVER)) {
                $server['CONTENT_TYPE'] = $_SERVER['HTTP_CONTENT_TYPE'];
            }
        }

        $request = self::createFromFactory($_GET, $_POST, array(), $_COOKIE, $_FILES, $server);

        return $request;
    }

    public function setAttributes($attributes)
    {

        foreach ($attributes as $k=>$attr) {
            $this->attributes->set($k,$attr);
        }

    }

    protected function prepareUri()
    {
        $requestUri = '';

        if ($this->server->has('REQUEST_URI')) {
            $requestUri = $this->server->get('REQUEST_URI');

            $schemeAndHttpHost = $this->getSchemeAndHttpHost();
            if (strpos($requestUri, $schemeAndHttpHost) === 0) {
                $requestUri = substr($requestUri, strlen($schemeAndHttpHost));
            }

        } elseif ($this->server->has('ORIG_PATH_INFO')) {

            $requestUri = $this->server->get('ORIG_PATH_INFO');
            if ('' != $this->server->get('QUERY_STRING')) {
                $requestUri .= '?'.$this->server->get('QUERY_STRING');
            }

        }

        $this->server->set('REQUEST_URI', $requestUri);

        return $requestUri;
    }

    public function getSchemeAndHttpHost()
    {
        return $this->getScheme().'://'.$this->getHost();
    }

    public function getHost()
    {
        $host = $this->server->get('SERVER_ADDR', '');

        return strtolower(preg_replace('/:\d+$/', '', trim($host)));

    }

    public function getScheme()
    {
        return 'http';
    }

    public function getBaseUrl()
    {
        if (null === $this->baseUrl) {
            $this->baseUrl = $this->prepareUrl();
        }

        return $this->baseUrl;
    }

    /**
     * Prepares the base URL.
     *
     * @return string
     */
    protected function prepareUrl()
    {
        $filename = basename($this->server->get('SCRIPT_FILENAME'));

        if (basename($this->server->get('SCRIPT_NAME')) === $filename) {
            $base = $this->server->get('SCRIPT_NAME');
        } elseif (basename($this->server->get('PHP_SELF')) === $filename) {
            $base = $this->server->get('PHP_SELF');
        } else {

            $path = $this->server->get('PHP_SELF', '');
            $file = $this->server->get('SCRIPT_FILENAME', '');
            $segs = explode('/', trim($file, '/'));
            $segs = array_reverse($segs);
            $index = 0;
            $last = count($segs);
            $base = '';

            do {
                $seg = $segs[$index];
                $base = '/'.$seg.$base;
                ++$index;
            } while ($last > $index && (false !== $pos = strpos($path, $base)) && 0 != $pos);

        }

        $requestUri = $this->getRequestUri();
        if ($base && false !== $prefix = $this->getUrlPrefix($requestUri, $base)) {
            return $prefix;
        }

        if ($base && false !== $prefix = $this->getUrlPrefix($requestUri, rtrim(dirname($base), '/').'/')) {
            return rtrim($prefix, '/');
        }

        $truncatedRequestUri = $requestUri;
        if (false !== $pos = strpos($requestUri, '?')) {
            $truncatedRequestUri = substr($requestUri, 0, $pos);
        }

        $basename = basename($base);
        if (empty($basename) || !strpos(rawurldecode($truncatedRequestUri), $basename)) {
            return '';
        }

        if (strlen($requestUri) >= strlen($base) && (false !== $pos = strpos($requestUri, $base)) && $pos !== 0) {
            $base = substr($requestUri, 0, $pos + strlen($base));
        }

        return rtrim($base, '/');
    }

    private function getUrlPrefix($string, $prefix)
    {
        if (0 !== strpos(rawurldecode($string), $prefix)) {
            return false;
        }

        $len = strlen($prefix);

        if (preg_match(sprintf('#^(%%[[:xdigit:]]{2}|.){%d}#', $len), $string, $match)) {
            return $match[0];
        }

        return false;
    }

    public function getRequestUri()
    {
        if (null === $this->requestUri) {
            $this->requestUri = $this->prepareUri();
        }

        return $this->requestUri;
    }

    private static function createFromFactory(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null){
        if(self::$factory){

            $request = call_user_func(self::$factory, $query, $request, $attributes, $cookies, $files, $server, $content);

            if (!$request instanceof Request) {
                throw new \Exception('The Request factory must return an instance of Syph\Http\Base\Request.');
            }
            return $request;
        }
        return new static($query, $request, $attributes, $cookies, $files, $server, $content);
        
    }

    public function getName()
    {
        return self::SERVICE_NAME;
    }
}
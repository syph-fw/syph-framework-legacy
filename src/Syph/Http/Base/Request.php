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
    const CLIENT_IP = 'client_ip';
    const CLIENT_HOST = 'client_host';
    const CLIENT_PROTO = 'client_proto';
    const CLIENT_PORT = 'client_port';

    const HEAD = 'HEAD';
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const PATCH = 'PATCH';
    const DELETE = 'DELETE';

    protected static $factory;

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
     * @var array
     */
    protected $language;

    /**
     * @var array
     */
    protected $charset;

    /**
     * @var array
     */
    protected $encoding;

    /**
     * @var array
     */
    protected $acceptContentType;

    /**
     * @var string
     */
    protected $pathInfo;

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
     * @var string
     */
    protected $format;

    /**
     * @var object
     */
    protected $session;

    /**
     * @var string
     */
    protected $locale;

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
//        $this->cookies = new ParameterBag($cookies);
//        $this->files = new FileBag($files);

        $this->server = new Server($server);
        $this->headers = new Header($this->server->getHeaders());

        $this->content = $content;
        $this->languages = null;
        $this->charset = null;
        $this->encoding = null;
        $this->acceptContentType = null;
        $this->pathInfo = null;
        $this->requestUri = null;
        $this->baseUrl = null;
        $this->basePath = null;
        $this->method = $server['REQUEST_METHOD'];
        $this->format = null;

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

//        if (0 === strpos($request->headers->get('CONTENT_TYPE'), 'application/x-www-form-urlencoded')
//            && in_array(strtoupper($request->server->get('REQUEST_METHOD', 'GET')), array('PUT', 'DELETE', 'PATCH'))
//        ) {
//            parse_str($request->getContent(), $data);
//            $request->request = new HttpVerbose($data);
//        }

        return $request;
    }

    public function setAttributes($attributes)
    {

        foreach ($attributes as $k=>$attr) {
            $this->attributes->set($k,$attr);
        }

    }

    protected function prepareRequestUri()
    {
        $requestUri = '';

        if ($this->server->has('REQUEST_URI')) {
            $requestUri = $this->server->get('REQUEST_URI');
            // HTTP proxy reqs setup request URI with scheme and host [and port] + the URL path, only use URL path
            $schemeAndHttpHost = $this->getSchemeAndHttpHost();
            if (strpos($requestUri, $schemeAndHttpHost) === 0) {
                $requestUri = substr($requestUri, strlen($schemeAndHttpHost));
            }
        } elseif ($this->server->has('ORIG_PATH_INFO')) {
            // IIS 5.0, PHP as CGI
            $requestUri = $this->server->get('ORIG_PATH_INFO');
            if ('' != $this->server->get('QUERY_STRING')) {
                $requestUri .= '?'.$this->server->get('QUERY_STRING');
            }
            $this->server->remove('ORIG_PATH_INFO');
        }

        // normalize the request URI to ease creating sub-requests from this request
        $this->server->set('REQUEST_URI', $requestUri);

        return $requestUri;
    }

    public function getPathInfo()
    {
        if (null === $this->pathInfo) {
            $this->pathInfo = $this->preparePathInfo();
        }

        return $this->pathInfo;
    }



    public function getSchemeAndHttpHost()
    {
        return $this->getScheme().'://'.$this->getHttpHost();
    }

    public function getHttpHost()
    {
        return $this->getHost();

    }

    public function getHost()
    {

        $host = $this->server->get('SERVER_ADDR', '');
        // trim and remove port number from host
        // host is lowercase as per RFC 952/2181
        $host = strtolower(preg_replace('/:\d+$/', '', trim($host)));


        return $host;
    }

    public function getScheme()
    {
        return 'http';
    }

    public function getBaseUrl()
    {
        if (null === $this->baseUrl) {
            $this->baseUrl = $this->prepareBaseUrl();
        }

        return $this->baseUrl;
    }

    /**
     * Prepares the base URL.
     *
     * @return string
     */
    protected function prepareBaseUrl()
    {
        $filename = basename($this->server->get('SCRIPT_FILENAME'));

        if (basename($this->server->get('SCRIPT_NAME')) === $filename) {
            $baseUrl = $this->server->get('SCRIPT_NAME');
        } elseif (basename($this->server->get('PHP_SELF')) === $filename) {
            $baseUrl = $this->server->get('PHP_SELF');
        } elseif (basename($this->server->get('ORIG_SCRIPT_NAME')) === $filename) {
            $baseUrl = $this->server->get('ORIG_SCRIPT_NAME'); // 1and1 shared hosting compatibility
        } else {
            // Backtrack up the script_filename to find the portion matching
            // php_self
            $path = $this->server->get('PHP_SELF', '');
            $file = $this->server->get('SCRIPT_FILENAME', '');
            $segs = explode('/', trim($file, '/'));
            $segs = array_reverse($segs);
            $index = 0;
            $last = count($segs);
            $baseUrl = '';
            do {
                $seg = $segs[$index];
                $baseUrl = '/'.$seg.$baseUrl;
                ++$index;
            } while ($last > $index && (false !== $pos = strpos($path, $baseUrl)) && 0 != $pos);
        }

        // Does the baseUrl have anything in common with the request_uri?
        $requestUri = $this->getRequestUri();

        if ($baseUrl && false !== $prefix = $this->getUrlencodedPrefix($requestUri, $baseUrl)) {
            // full $baseUrl matches
            return $prefix;
        }



        if ($baseUrl && false !== $prefix = $this->getUrlencodedPrefix($requestUri, rtrim(dirname($baseUrl), '/').'/')) {
            return rtrim($prefix, '/');
        }

        $truncatedRequestUri = $requestUri;
        if (false !== $pos = strpos($requestUri, '?')) {
            $truncatedRequestUri = substr($requestUri, 0, $pos);
        }

        $basename = basename($baseUrl);
        if (empty($basename) || !strpos(rawurldecode($truncatedRequestUri), $basename)) {
            return '';
        }

        if (strlen($requestUri) >= strlen($baseUrl) && (false !== $pos = strpos($requestUri, $baseUrl)) && $pos !== 0) {
            $baseUrl = substr($requestUri, 0, $pos + strlen($baseUrl));
        }

        return rtrim($baseUrl, '/');
    }

    private function getUrlencodedPrefix($string, $prefix)
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

    /**
     * Prepares the base path.
     *
     * @return string base path
     */
    protected function prepareBasePath()
    {
        $filename = basename($this->server->get('SCRIPT_FILENAME'));
        $baseUrl = $this->getBaseUrl();
        if (empty($baseUrl)) {
            return '';
        }

        if (basename($baseUrl) === $filename) {
            $basePath = dirname($baseUrl);
        } else {
            $basePath = $baseUrl;
        }

        if ('\\' === DIRECTORY_SEPARATOR) {
            $basePath = str_replace('\\', '/', $basePath);
        }

        return rtrim($basePath, '/');
    }

    public function getRequestUri()
    {
        if (null === $this->requestUri) {
            $this->requestUri = $this->prepareRequestUri();
        }

        return $this->requestUri;
    }

    /**
     * Prepares the path info.
     *
     * @return string path info
     */
    protected function preparePathInfo()
    {
        $baseUrl = $this->getBaseUrl();

        if (null === ($requestUri = $this->getRequestUri())) {
            return '/';
        }

        $pathInfo = '/';

        // Remove the query string from REQUEST_URI
        if ($pos = strpos($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, $pos);
        }

        $pathInfo = substr($requestUri, strlen($baseUrl));
        if (null !== $baseUrl && (false === $pathInfo || '' === $pathInfo)) {
            // If substr() returns false then PATH_INFO is set to an empty string
            return '/';
        } elseif (null === $baseUrl) {
            return $requestUri;
        }

        return (string) $pathInfo;
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
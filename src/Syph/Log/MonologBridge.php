<?php
/**
 * Created by PhpStorm.
 * User: prapa
 * Date: 20/06/2016
 * Time: 17:31
 */

namespace Syph\Log;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class MonologBridge
{

    protected $logger;
    protected $default_stream;
    /**
     * MonologBridge constructor.
     */
    public function __construct($file)
    {
        // Create some handlers
        $this->default_stream = new StreamHandler($file, Logger::DEBUG);
        $firephp = new FirePHPHandler();

        // Create the main logger of the app
        $this->logger = new Logger('syph');
        $this->logger->pushHandler($this->default_stream);
        $this->logger->pushHandler($firephp);
    }
}
<?php

/**
 * Created by PhpStorm.
 * User: prapa
 * Date: 04/04/2016
 * Time: 12:14
 */

namespace Syph\Http\Session\Storage;

use Syph\Http\Session\Handler\PHPSessionHandler;
use Syph\Http\Session\SessionInterface;

class SessionStorage implements SessionStorageInterface
{
    /**
     * @var SessionMetaBottle[]
     */
    private $metaBottle;
    /**
     * @var SessionBottle[]
     */
    private $bottles;

    /**
     * @var PHPSessionHandler
     */
    private $handler;

    private $isStart = false;

    /**
     * SessionStorage constructor.
     */
    public function __construct(SessionMetaBottle $metaBottle = null)
    {
        ini_set('session.use_cookies', 1);

        if (PHP_VERSION_ID >= 50400) {
            session_register_shutdown();
        } else {
            register_shutdown_function('session_write_close');
        }

        if (null === $metaBottle) {
            $metaBottle = new SessionMetaBottle();
        }
        $this->metadataBag = $metaBottle;

        $this->handler = new PHPSessionHandler();

        $this->isStart = true;
    }


    public function start($name = null)
    {
        if(!is_null($name)){
            session_name($name);
        }

        if (!session_start()) {
            throw new \RuntimeException('Failed to start the session');
        }

        $this->load();
    }

    public function save()
    {
        session_write_close();
        $this->isStart = false;
    }

    public function isStarted()
    {
        return $this->isStart;
    }

    public function getId()
    {
        $this->handler->getId();
    }

    public function setId($id)
    {
        $this->handler->setId($id);
    }

    public function getName()
    {
        $this->handler->getName();
    }

    public function setName($name)
    {
        $this->handler->setName($name);
    }

    public function clear()
    {
        foreach ($this->bottles as $bottle) {
            $bottle->clear();
        }
        $_SESSION = array();
        $this->load();
    }

    public function registerBottle(SessionBottle $bottle)
    {
        $this->bottles[$bottle->getName()] = $bottle;
    }

    /**
     * @param $name
     * @return SessionBottle
     */
    public function getBottle($name)
    {
        return $this->bottles[$name];
    }

    public function load(){
        $session = &$_SESSION;

        foreach ($this->bottles as $bottle) {
            $key = $bottle->getStorageId();
            $session[$key] = isset($session[$key]) ? $session[$key] : array();
            $bottle->load($session[$key]);
        }
    }
}
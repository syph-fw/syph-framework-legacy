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
        if (null === $metaBottle) {
            $metaBottle = new SessionMetaBottle();
        }
        $this->metadataBag = $metaBottle;

        $this->handler = new PHPSessionHandler();

        $this->isStart = true;
    }


    public function start()
    {
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

        foreach ($this->bottles as $bottle) {
            $key = $bottle->getStorageId();
            $_SESSION[$key] = isset($_SESSION[$key]) ? $_SESSION[$key] : array();
            $bottle->load($_SESSION[$key]);
        }

    }
}
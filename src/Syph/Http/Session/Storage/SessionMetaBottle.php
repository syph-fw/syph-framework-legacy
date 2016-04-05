<?php
/**
 * Created by PhpStorm.
 * User: prapa
 * Date: 04/04/2016
 * Time: 13:11
 */

namespace Syph\Http\Session\Storage;


class SessionMetaBottle
{
    const DEFAULT_ITERATOR_HASH = 2;
    private $hash;
    private $name;

    /**
     * SessionMetaBottle constructor.
     * @param $hash
     */
    public function __construct($hash = null)
    {
        $this->hash = null === $hash ? self::makeSessionHash('syph',self::DEFAULT_ITERATOR_HASH): $hash;
    }


    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param mixed $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public static function makeSessionHash($string,$iterator = null){
        return null === $iterator || $iterator === 0 ? md5($string): self::makeSessionHash($string,$iterator-1) ;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: prapa
 * Date: 04/04/2016
 * Time: 13:47
 */

namespace Syph\Http\Session\Storage;


use Traversable;

class SessionBottle implements \IteratorAggregate
{
    /**
     * @var string
     */
    private $name = 'syph_session_attr';
    /**
     * @var array
     */
    private $attributes;
    /**
     * @var string
     */
    private $storageId;

    /**
     * SessionBottle constructor.
     * @param $atribute
     */
    public function __construct($storageId = 'syph_attr')
    {
        $this->storageId = $storageId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function get($name)
    {
        return $this->attributes[$name];
    }

    /**
     * @param $name
     * @param $value
      */
    public function set($name,$value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * @return array
     */
    public function all(){
        return $this->attributes;
    }

    /**
     * @return string
     */
    public function getStorageId()
    {
        return $this->storageId;
    }

    /**
     * @param string $storageId
     */
    public function setStorageId($storageId)
    {
        $this->storageId = $storageId;
    }



    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->attributes);
    }

    public function has($name)
    {
        return array_key_exists($name,$this->attributes);
    }

    public function clear()
    {
        $this->attributes = array();
    }

    public function remove($name)
    {
        if($this->has($name))
            unset($this->attributes[$name]);
    }

    public function load(&$attr)
    {

        $this->attributes = &$attr;
    }
}
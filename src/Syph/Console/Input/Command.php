<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 18/03/2016
 * Time: 16:04
 */

namespace Syph\Console\Input;


class Command implements CommandInterface
{
    private $name;

    /**
     * Command constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
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


    public function run()
    {
        print "Executei algo bom!\n";
    }
}
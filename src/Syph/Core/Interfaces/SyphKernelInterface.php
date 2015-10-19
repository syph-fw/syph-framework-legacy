<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 27/09/2015
 * Time: 11:11
 */

namespace Syph\Core\Interfaces;


use Syph\AppBuilder\Interfaces\AppInterface;

interface SyphKernelInterface {
    /**
     * @return AppInterface[] An array of app instances.
     */
    public function registerApps();
} 
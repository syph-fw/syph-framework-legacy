<?php
/**
 * Created by PhpStorm.
 * User: prapa
 * Date: 20/06/2016
 * Time: 17:33
 */

namespace Syph\Log;


use Syph\Core\Kernel;
use Syph\DependencyInjection\ServiceInterface;

class SyphLogger extends MonologBridge implements ServiceInterface
{

    /**
     * SyphLogger constructor.
     */
    public function __construct(Kernel $kernel)
    {
        $today = new \DateTime();
        parent::__construct(sprintf($kernel->getSyphAppDir().'/../storage/logs/syph-%s.log',$today->format('Y-m-d')));
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function getName()
    {
        return 'logger';
    }


}
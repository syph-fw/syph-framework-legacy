<?php
/**
 * Created by PhpStorm.
 * User: prapa
 * Date: 14/10/2016
 * Time: 14:55
 */

namespace Syph\Security\Firewall;


use Syph\EventDispatcher\Interfaces\EventInterface;

class FirewallLoadedEvent implements EventInterface
{

    public function getInfo()
    {
        return 'Event fired when firewall is loaded';
    }
}
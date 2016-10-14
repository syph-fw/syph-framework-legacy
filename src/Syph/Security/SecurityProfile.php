<?php
/**
 * Created by PhpStorm.
 * User: prapa
 * Date: 14/10/2016
 * Time: 16:00
 */

namespace Syph\Security;


use Syph\Security\Auth\Authentication;
use Syph\Security\Auth\Authorization;

class SecurityProfile
{
    /**
     * @var Authentication $authentication
     */
    private $authentication;

    /**
     * @var Authorization $authorization
     */
    private $authorization;

    /**
     * SecurityProfile constructor.
     * @param Authentication $authentication
     * @param Authorization $authorization
     */
    public function __construct(Authentication $authentication, Authorization $authorization)
    {
        $this->authentication = $authentication;
        $this->authorization = $authorization;
    }

    public function getUser()
    {
        return $this->authentication;
    }

}
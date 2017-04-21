<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 14/04/2017
 * Time: 14:44
 */

namespace Syph\Security\Csrf;


use Syph\DependencyInjection\ServiceInterface;
use Syph\Http\Session\Session;
use Syph\Security\Crypt\Cube;

class Csrf implements ServiceInterface
{

    const TOKEN_NAME = "syph_token";
    const SERVICE_NAME = "security.csrf";
    /**
     * @var Session $session
     */
    public $session;

    /**
     * Csrf constructor.
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getToken($new = NULL)
    {

        $token = $this->session->get(self::TOKEN_NAME);
        if ($new == TRUE OR ! $token)
        {
            if (function_exists('openssl_random_pseudo_bytes'))
            {
                $token = base64_encode(openssl_random_pseudo_bytes(32));
            }
            else
            {
                $token = Cube::generateHash();
            }

            $this->session->set(self::TOKEN_NAME, $token);
        }

        return $token;
    }

    public function checkToken($token){
        return Cube::checkHashEqual($this->getToken(),$token);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::SERVICE_NAME;
    }
}
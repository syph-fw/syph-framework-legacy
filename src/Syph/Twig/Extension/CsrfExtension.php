<?php

namespace Syph\Twig\Extension;

use Syph\Security\Csrf\Csrf;

@trigger_error('The '.__NAMESPACE__.'\AssetsExtension class is deprecated since version 2.7 and will be removed in 3.0. Use the Symfony\Bridge\Twig\Extension\AssetExtension class instead.', E_USER_DEPRECATED);


class CsrfExtension extends \Twig_Extension
{

    private $token;
    /**
     * AssetsExtension constructor.
     */
    public function __construct(Csrf $csrf)
    {
        $this->token = $csrf->getToken();
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('csrf', array($this, 'getCsrfToken')),
            new \Twig_SimpleFunction('assets_version', array($this, 'getCsrfVersion')),
        );
    }

    public function getCsrfToken()
    {
        return $this->token;
    }

    public function getCsrfVersion()
    {
        return '0.1';
    }


    public function getName()
    {
        return 'csrf';
    }

}
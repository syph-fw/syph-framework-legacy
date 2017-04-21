<?php

namespace Syph\Twig\Extension;

use Syph\Http\Base\Request;

@trigger_error('The '.__NAMESPACE__.'\AssetsExtension class is deprecated since version 2.7 and will be removed in 3.0. Use the Symfony\Bridge\Twig\Extension\AssetExtension class instead.', E_USER_DEPRECATED);


class AssetsExtension extends \Twig_Extension
{

    private $baseUrl;
    /**
     * AssetsExtension constructor.
     */
    public function __construct(Request $request)
    {
        $this->baseUrl = $request->getBaseUrl();
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('asset', array($this, 'getAssetUrl')),
            new \Twig_SimpleFunction('assets_version', array($this, 'getAssetsVersion')),
        );
    }

    public function getAssetUrl($path)
    {
        return $this->baseUrl.$path;
    }

    public function getAssetsVersion()
    {
        return '0.3';
    }


    public function getName()
    {
        return 'assets';
    }

}
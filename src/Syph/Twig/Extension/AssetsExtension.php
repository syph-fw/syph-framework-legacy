<?php

namespace Syph\Twig\Extension;

@trigger_error('The '.__NAMESPACE__.'\AssetsExtension class is deprecated since version 2.7 and will be removed in 3.0. Use the Symfony\Bridge\Twig\Extension\AssetExtension class instead.', E_USER_DEPRECATED);


class AssetsExtension extends \Twig_Extension
{



    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('asset', array($this, 'getAssetUrl')),
            new \Twig_SimpleFunction('assets_version', array($this, 'getAssetsVersion')),
        );
    }

    public function getAssetUrl($path)
    {
        return $path;
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
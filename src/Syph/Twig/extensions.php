<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 20/04/2017
 * Time: 21:34
 */
return [
    'assets' => [
        'class' => 'Syph\Twig\Extension\AssetsExtension',
        'args' => [
            'http.request'
        ]
    ],
    'csrf' => [
        'class' => 'Syph\Twig\Extension\CsrfExtension',
        'args' => [
            'security.csrf'
        ]
    ],
];
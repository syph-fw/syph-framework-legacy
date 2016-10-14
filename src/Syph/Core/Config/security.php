<?php
/**
 * Created by PhpStorm.
 * User: prapa
 * Date: 14/10/2016
 * Time: 15:22
 */
return [
    'security' => [
        'model'=>'',
        'provider'=>'database',// ('database','memory')
        'roles'=>[
            'user'=>[],
            'admin'=>['user'],
            'super_admin'=>['user','admin'],
        ],
        'firewall' => [
            'main' => [
                'login' => '/admin',
                'login_checker' => '/admin/check',
                //routes mapped in firewall
                'routes'=>[
                    //route_path => role
                    '/admin'        => 'ADMIN',
                    '/admin/test'   => 'ADMIN',
                ]
            ]
        ]
    ]
];
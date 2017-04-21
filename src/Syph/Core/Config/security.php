<?php
/**
 * Created by PhpStorm.
 * User: prapa
 * Date: 14/10/2016
 * Time: 15:22
 */
return [
    'roles'=>[
        'user'=>[],
        'admin'=>['user'],
        'super_admin'=>['user','admin'],
    ]
];
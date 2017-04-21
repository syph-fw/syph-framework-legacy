<?php

return array(

    'http.core' => array(
        'class'=>'Syph\\Http\\Http',
        'strategy'=>'instance',
        'args'=> array(
            'controller.solve' => array(
                'class'=>'Syph\\Controller\\SolveController',
                'strategy'=>'instance',
                'args'=> array(
                    'container' => array(
                        'class'=>'Syph\\Container\\Container',
                        'strategy'=>'instance',
                    ),
                    'controller.parse' => array(
                        'class'=>'Syph\\Controller\\ParseController',
                        'strategy'=>'instance',
                        'args'=>array(
                            'kernel' => array(
                                'class'=>'Syph\\Core\\Kernel',
                                'strategy'=>'instance'
                            )
                        )
                    )
                )
            )
        )
    ),
    'event.dispatcher' => array(
        'class'=>'Syph\\EventDispatcher\\EventDispatcher',
        'strategy'=>'instance',
        'args'=>array(
            'container' => array(
                'class'=>'Syph\\Container\\Container',
                'strategy'=>'instance',
            )
        )
    ),
    'request.start.listener' => array(
        'class'=>'Syph\\Core\\EventListeners\\RequestStartListener',
        'strategy'=>'event_listener'
    ),
    'firewall.provider' => array(
        'class'=>'Syph\\Security\\Firewall\\FirewallProvider',
        'strategy'=>'event_listener'
    ),
    'view.renderer' => array(
        'class'=>'Syph\\View\\Renderer',
        'strategy'=>'instance',
        'args'=>array(
            'http.request' => array(
                'class'=>'Syph\\Http\\Base\\Request',
                'strategy'=>'instance'
            )
        )
    ),
    'cache' => array(
        'class'=>'Syph\\Cache\\FileCache',
        'strategy'=>'instance',
    ),
    'logger' => array(
        'class'=>'Syph\\Log\\SyphLogger',
        'strategy'=>'instance',
        'args'=>array(
            'kernel' => array()
        )

    ),
    'db.core' => array(
        'class'=>'Syph\\DB\\DB',
        'strategy'=>'instance',
        'args'=>array(
            'kernel' => array()
        )

    ),

);
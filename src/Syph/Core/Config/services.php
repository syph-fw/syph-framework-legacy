<?php

return array(
    'services' => array(
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
        'routing.router' => array(
            'class'=>'Syph\\Routing\\Router',
            'strategy'=>'instance',
            'args'=>array(
                'routing.urlmatcher' => array(
                    'class'=>'Syph\\Routing\\UrlMatcher',
                    'strategy'=>'instance'
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
        'kernel.boot.listener' => array(
            'class'=>'Syph\\Core\\EventListeners\\KernelBootListener',
            'strategy'=>'event_listener',
            'args'=> array(
                'name'=>'routing.router'
            )
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
        'http.session' => array(
            'class'=>'Syph\\Http\\Session\\Session',
            'strategy'=>'instance'
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
    )
);
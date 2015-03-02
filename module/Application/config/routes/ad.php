<?php

return [
    'create' => [
        'type' => 'Segment',
        'options' => array(
            'route' => '/create',
            'defaults' => array(
                'controller' => 'Application\Controller\Ad',
                'action' => 'create',
            ),
        ),
        'may_terminate' => true,
    ],
    'upload' => [
        'type' => 'Segment',
        'options' => array(
            'route' => '/upload[/:option][/:folder][/:name]',
            'defaults' => array(
                'controller' => 'Application\Controller\Ad',
                'action' => 'upload',
            ),
        ),
        'may_terminate' => true,
    ]
];

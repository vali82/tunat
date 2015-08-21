<?php

return [
    'update' => [
        'type' => 'Segment',
        'options' => array(
            'route' => '/update',
            'defaults' => array(
                'controller' => 'Application\Controller\MyAccount',
                'action' => 'update',
            ),
        ),
        'may_terminate' => true,
    ],
    'removeLogo' => [
        'type' => 'Segment',
        'options' => array(
            'route' => '/remove-logo[/:token]',
            'defaults' => array(
                'controller' => 'Application\Controller\MyAccount',
                'action' => 'removeLogo',
            ),
            'constraints' => [
                'token' => '[a-z0-9A-Z]+'
            ],
        ),
        'may_terminate' => true,
    ],
];

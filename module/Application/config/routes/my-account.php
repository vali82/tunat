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
    'changePassword' => [
        'type' => 'Segment',
        'options' => array(
            'route' => '/change-password',
            'defaults' => array(
                'controller' => 'Application\Controller\MyAccount',
                'action' => 'update',
            ),
        ),
        'may_terminate' => true,
    ],
    'myAds' => [
        'type' => 'Segment',
        'options' => array(
            'route' => '/my-ads[/:status][/:p]',
            'defaults' => array(
                'controller' => 'Application\Controller\MyAccount',
                'action' => 'myAds',
            ),
            'constraints' => array(
                'status' => '(active|expired)',
                'p' => '[0-9]+',
            ),
        ),
        'may_terminate' => true,
    ],
    'changeStatus' => [
        'type' => 'Segment',
        'options' => array(
            'route' => '/change-status[/:mode][/:id][/:token]',
            'constraints' => [
                'id' => '[0-9]+',
                'token' => '[a-z0-9A-Z]+'
            ],
            'defaults' => array(
                'controller' => 'Application\Controller\Ad',
                'action' => 'changeStatus',
            ),
        ),
        'may_terminate' => true,
    ]
];

<?php

return [
    'create' => [
        'type' => 'Segment',
        'options' => array(
            'route' => '/create[/:id]',
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
    ],
    'getContact' => [
        'type' => 'Segment',
        'options' => array(
            'route' => '/get-contact[/:id]',
            'constraints' => [
                'id' => '[0-9]+',
            ],
            'defaults' => array(
                'controller' => 'Application\Controller\Ad',
                'action' => 'getContact',
            ),
        ),
        'may_terminate' => true,
    ],
];

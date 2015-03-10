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
    ],
    'piese' => [
        'type' => 'Segment',
        'options' => array(
            'route' => 'piese[/:car_make][/:car_class][/:car_model][/:parts_main][/:p][/:ad_id]',
            'defaults' => array(
                '__NAMESPACE__' => 'Application\Controller',
                'controller' => 'Ad',
                'action' => 'piese',
            ),
        ),
        'may_terminate' => true,
//        'child_routes' => include __DIR__ . '/ad.php'
    ],
];

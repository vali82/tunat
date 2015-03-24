<?php

return [
    'ad' => [
        'type' => 'Literal',
        'options' => array(
            'route' => 'ad',
            'defaults' => array(
                'controller' => 'Application\Controller\Ad',
                'action' => 'index',
            ),
        ),
        'may_terminate' => false,
        'child_routes' => include __DIR__ . '/ad.php'
    ],

    'piese' => [
        'type' => 'Segment',
        'options' => array(
            'route' => 'piese[/:categories][/:car_class][/:p][/:ad_id]',
            'defaults' => array(
                '__NAMESPACE__' => 'Application\Controller',
                'controller' => 'Ad',
                'action' => 'piese',
            ),
        ),
        'may_terminate' => true,
//        'child_routes' => include __DIR__ . '/ad.php'
    ],

    'displayImage' => [
        'type' => 'Segment',
        'options' => array(
            'route' => 'display-image[/:folder][/:id][/:size][/:ext]',
            'defaults' => array(
                '__NAMESPACE__' => 'Application\Controller',
                'controller' => 'Simple',
                'action' => 'displayImage',
            ),
            'constraints' => array(
                'folder' => '[a-z0-9x]+',
                'id' => '[a-z0-9_]+',
                'size' => '[0-9x]+',
                'ext' => '(.jpg|.png|.gif)'
                //'h' => '[0-9]+',
            ),
        ),
        'may_terminate' => true,
    ],
];

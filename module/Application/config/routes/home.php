<?php

return [

    'login' => array(
        'type' => 'Literal',
        'options' => array(
            'route'    => 'login',
            'defaults' => array(
                'controller' => 'Application\Controller\LoginRegister',
                'action'     => 'login',
            ),
            'constraints' => array(
//                'iHash' => '[a-zA-Z0-9]+'
            ),
        ),
    ),

    'register' => array(
        'type' => 'Literal',
        'options' => array(
            'route'    => 'register',
            'defaults' => array(
                'controller' => 'Application\Controller\LoginRegister',
                'action'     => 'register',
            ),
            'constraints' => array(
//                'iHash' => '[a-zA-Z0-9]+'
            ),
        ),
    ),

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
            'route' => 'piese[/:categories][/:car_class][/:ad_id][/:p][/:search]',
            'defaults' => array(
                '__NAMESPACE__' => 'Application\Controller',
                'controller' => 'Ad',
                'action' => 'piese',
            ),
            'constraints' => array(
               // 'categories' => '(camioane|dube|"utilaje-agricole")',
            )
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

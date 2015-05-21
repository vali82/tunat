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
        ),
    ),
    'logout' => array(
        'type' => 'Literal',
        'options' => array(
            'route'    => 'logout',
            'defaults' => array(
                'controller' => 'Application\Controller\LoginRegister',
                'action'     => 'logout',
            ),
        ),
    ),
    'afterlogin' => array(
        'type' => 'Literal',
        'options' => array(
            'route'    => 'after-login',
            'defaults' => array(
                'controller' => 'Application\Controller\LoginRegister',
                'action'     => 'afterLogin',
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
        ),
    ),

    'forgotPassword' => array(
        'type' => 'Literal',
        'options' => array(
            'route'    => 'forgot-password',
            'defaults' => array(
                'controller' => 'Application\Controller\LoginRegister',
                'action'     => 'forgotPassword',
            ),
        ),
    ),

    'resetPassword' => array(
        'type' => 'Segment',
        'options' => array(
            'route'    => 'reset-password[/:hash]',
            'defaults' => array(
                'controller' => 'Application\Controller\LoginRegister',
                'action'     => 'resetPassword',
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

    'offers' => [
        'type' => 'Literal',
        'options' => array(
            'route' => 'oferte',
            'defaults' => array(
                'controller' => 'Application\Controller\Offers',
                'action' => 'index',
            ),
        ),
        'may_terminate' => false,
        'child_routes' => include __DIR__ . '/offers.php'
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

    'myAccount' => [
        'type' => 'Literal',
        'options' => array(
            'route' => 'my-account',
            'defaults' => array(
                'controller' => 'Application\Controller\myAccount',
                'action' => 'index',
            ),
        ),
        'may_terminate' => true,
        'child_routes' => include __DIR__ . '/my-account.php'
    ],

    'terms' => [
        'type' => 'Literal',
        'options' => array(
            'route' => 'termeni-si-conditii',
            'defaults' => array(
                'controller' => 'Application\Controller\Index',
                'action' => 'terms',
            ),
        ),
        'may_terminate' => true,
    ],
    'privacy' => [
        'type' => 'Literal',
        'options' => array(
            'route' => 'confidentialitate',
            'defaults' => array(
                'controller' => 'Application\Controller\Index',
                'action' => 'privacy',
            ),
        ),
        'may_terminate' => true,
    ],
    'contact' => [
        'type' => 'Literal',
        'options' => array(
            'route' => 'contact',
            'defaults' => array(
                'controller' => 'Application\Controller\Index',
                'action' => 'contact',
            ),
        ),
        'may_terminate' => true,
    ]
];

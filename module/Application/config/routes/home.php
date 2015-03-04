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
//                                'sectype' => '(kg_admin|educator|parent|kindergarten|photos|child|admin_kp|users|activities)',
                'id' => '[a-z0-9_]+',
                'size' => '[0-9x]+',
                'ext' => '(.jpg|.png|.gif)'
                //'h' => '[0-9]+',
            ),
        ),
        'may_terminate' => true,
    ],

    'piese' => [
        'type' => 'Segment',
        'options' => array(
            'route' => 'piese[/:car_make][/:car_class][/:car_model][/:parts_main]',
            'defaults' => array(
                '__NAMESPACE__' => 'Application\Controller',
                'controller' => 'Index',
                'action' => 'piese',
            ),
        ),
        'may_terminate' => true,
//        'child_routes' => include __DIR__ . '/ad.php'
    ],
];

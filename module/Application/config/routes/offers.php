<?php

return [
    'create' => [
        'type' => 'Segment',
        'options' => array(
            'route' => '/cerere[/:id]',
            'defaults' => array(
                'controller' => 'Application\Controller\Offers',
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
                'controller' => 'Application\Controller\Offers',
                'action' => 'upload',
            ),
        ),
        'may_terminate' => true,
    ],

];

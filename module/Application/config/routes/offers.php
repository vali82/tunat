<?php

return [
    'create' => [
        'type' => 'Segment',
        'options' => array(
            'route' => '/create[/:id]',
            'defaults' => array(
                'controller' => 'Application\Controller\Offers',
                'action' => 'create',
            ),
        ),
        'may_terminate' => true,
    ],
];

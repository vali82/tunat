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

];

<?php

return [
    'partners' => [
        'type' => 'Literal',
        'options' => array(
            'route' => '/parteneri',
            'defaults' => array(
                'controller' => 'Application\Controller\Index',
                'action' => 'partners',
            ),
        ),
        'may_terminate' => true,
    ],
    'sitemap' => [
        'type' => 'Literal',
        'options' => array(
            'route' => '/sitemap',
            'defaults' => array(
                'controller' => 'Application\Controller\Index',
                'action' => 'sitemap',
            ),
        ),
        'may_terminate' => true,
    ],
];

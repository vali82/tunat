<?php

return array(
    'cronInactivateOldAds' => array(
        'options' => array(
            'route' => 'inactivate-old-ads',
            /*'constraints' => array(
                'days' => '(1|7|14)',
            ),*/
            'defaults' => array(
                'controller' => 'Application\Controller\Ad',
                'action' => 'cronInactivateOldAds',
            )
        )
    ),
);
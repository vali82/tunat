<?php

return [
    array(
        'route' => 'home/ad/create',
        'roles' => array('parcauto')
    ),
    array(
        'route' => 'home/ad/upload',
        'roles' => array('parcauto')
    ),
    array(
        'route' => 'home/ad/piese',
        'roles' => array('parcauto', 'user', 'guest')
    ),
];
<?php

return array(
    array(
        'route' => 'home',
        'roles' => array('guest', 'user', 'parcauto')
    ),
    array(
        'route' => 'zfcuser/login',
        'roles' => array('guest')
    ),
    array(
        'route' => 'zfcuser/register',
        'roles' => array('guest')
    ),
    array(
        'route' => 'zfcuser',
        'roles' => array('parcauto', 'user')
    ),
    array(
        'route' => 'zfcuser/logout',
        'roles' => array('parcauto', 'user')
    ),
    array(
        'route' => 'home/displayImage',
        'roles' => array('parcauto', 'user', 'guest')
    ),
    array(
        'route' => 'home/piese',
        'roles' => array('parcauto', 'user', 'guest')
    ),
);
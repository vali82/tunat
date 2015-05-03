<?php

return array(
    array(
        'route' => 'home',
        'roles' => array('guest', 'user', 'parcauto')
    ),
    array(
        'route' => 'home/login',
        'roles' => array('guest')
    ),
    array(
        'route' => 'home/register',
        'roles' => array('guest')
    ),
    [
        'route' => 'home/forgotPassword',
        'roles' => ['guest']
    ],
    [
        'route' => 'home/resetPassword',
        'roles' => ['guest']
    ],
    array(
        'route' => 'scn-social-auth-user',
        'roles' => array('parcauto', 'user')
    ),
    array(
        'route' => 'scn-social-auth-user/logout',
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

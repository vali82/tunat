<?php

return array(
    array(
        'route' => 'home',
        'roles' => array('guest', 'parcauto')
    ),
    array(
        'route' => 'home/login',
        'roles' => array('guest')
    ),
    array(
        'route' => 'home/logout',
        'roles' => array('parcauto')
    ),
    array(
        'route' => 'home/afterlogin',
        'roles' => array('parcauto')
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
        'route' => 'home/displayImage',
        'roles' => array('parcauto', 'guest')
    ),
    array(
        'route' => 'home/piese',
        'roles' => array('parcauto', 'guest')
    ),
    array(
        'route' => 'scn-social-auth-user',
        'roles' => array('parcauto')
    ),
    array(
        'route' => 'scn-social-auth-user/logout',
        'roles' => array('parcauto')
    ),
    [
        'route' => 'scn-social-auth-user/login/provider',
        'roles' => ['guest']
    ],
    array('route' => 'scn-social-auth-hauth', 'roles' => array('guest')),
//    array('route' => 'scn-social-auth-user/authenticate', 'roles' => array('guest')),
    array('route' => 'scn-social-auth-user/authenticate/provider', 'roles' => array('guest')),
    [
        'route' => 'home/terms',
        'roles' => ['guest', 'parcauto']
    ],
    [
        'route' => 'home/privacy',
        'roles' => ['guest', 'parcauto']
    ],
    [
        'route' => 'home/contact',
        'roles' => ['guest', 'parcauto']
    ],
);

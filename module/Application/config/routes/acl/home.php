<?php

return array(
    array(
        'route' => 'home',
        'roles' => array('guest', 'user', 'parcauto')
    ),
    array(
        'route' => 'scn-social-auth-user/login',
        'roles' => array('guest')
    ),
    array(
        'route' => 'scn-social-auth-user/register',
        'roles' => array('guest')
    ),
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

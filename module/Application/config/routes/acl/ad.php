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
        'route' => 'home/ad/getContact',
        'roles' => array('parcauto', 'user', 'guest')
    ),
    array(
        'route' => 'home/ad/myAds',
        'roles' => array('parcauto')
    ),
    array(
        'route' => 'home/ad/changeStatus',
        'roles' => array('parcauto')
    ),
    [
        'route' => 'cronInactivateOldAds',
        'roles' => array('guest')
    ]
];

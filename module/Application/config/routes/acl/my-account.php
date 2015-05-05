<?php

return [
    array(
        'route' => 'home/myAccount',
        'roles' => array('parcauto')
    ),
    array(
        'route' => 'home/myAccount/update',
        'roles' => array('parcauto')
    ),
    [
        'route' => 'zfcuser/changepassword',
        'roles' => ['parcauto']
    ]
];

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

];

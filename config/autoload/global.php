<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(

    'timezone' => 'Europe/Bucharest',
    'consts' => [
        'carburant' => [
            'benzina',
            'diesel',
            'gaz',
            'hibrid',
            'electric'
        ],
        'cilindree' => [
            '0.6',
            '0.9',
            '1.0',
            '1.1',
            '1.2',
            '1.3',
            '1.4',
            '1.5',
            '1.6',
            '1.7',
            '1.8',
            '1.9',
            '2.0',
            '2.2',
            '2.4',
            '2.5',
            '2.7',
            '3.0',
            '3.2',
            '3.5',
            '4.0',
            '4.2',
            '4.5',
            '5.0',
            '5.5',
            '6.0',
            '7.0',
            '8.0'
        ]
    ]
    // ...
);

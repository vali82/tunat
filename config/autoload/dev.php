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
        // lista asta tre sa ramana in aceasta ordine forever
        'states' => [
            'Oricare',
            'Bucuresti',
            'Alba',
            'Arad',
            'Arges',
            'Bacau',
            'Bihor',
            'Bistrita-Nasaud',
            'Botosani',
            'Braila',
            'Brasov',
            'Buzau',
            'Calarasi',
            'Caras-Severin',
            'Cluj',
            'Constanta',
            'Covasna',
            'Dambovita',
            'Dolj',
            'Galati',
            'Giurgiu',
            'Gorj',
            'Harghita',
            'Hunedoara',
            'Ialomita',
            'Iasi',
            'Ilfov',
            'Maramures',
            'Mehedinti',
            'Mures',
            'Neamt',
            'Olt',
            'Prahova',
            'Salaj',
            'Satu Mare',
            'Sibiu',
            'Suceava',
            'Teleorman',
            'Timis',
            'Tulcea',
            'Valcea',
            'Vaslui',
            'Vrancea',
        ]
    ],
    'email' => array(
        'from' => array(
            'email' => "contact@tirbox.ro",
            'name' => "Tirbox",
        ),
        'contactformto' => 'contact@tirbox.ro',
        'contactformcc' => array(
    //            'office@mindmagnetsoftware.com',
    //            'office@mindmagnet.ro',
        ),
        'mandrill' => array(
            'key' => 'rQUJH3gglPTL_T4BmX6IuQ'
        )
    ),
    'site_names' => array(
        'http'=>'http://dev.tirbox.ro',
        'name' => 'Tirbox',
        'cool_url' => 'dev.tirbox.ro',
        'entity_zone' => 'http://dev.tirbox.ro/%1$s/%2$s',
        'domain' => 'dev.tirbox.ro'
    ),
    // ...
);

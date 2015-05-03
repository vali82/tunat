<?php

namespace Application\Models\Autoparks;

use Application\Models\DataMapper;

class ParksDM extends DataMapper
{
    public function __construct($adapter)
    {
        $this->adapter = $adapter;
        $this->model = new Park();
        $this->table_name = 'autoparks';

        $this->fields = array(
            'name',
            'email',
            'url',
            'address',
            'city',
            'state',
            'description',
            'tel1',
            'tel2',
            'tel3',
            'logo',
            'account_type'
        );

        /*$this->_primary_key_update = array('id');
        $this->_primary_key_delete = array('id');*/

    }
}
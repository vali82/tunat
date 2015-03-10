<?php

namespace Application\Models\Autoparks;

use Application\Models\DataMapper;

class ParkUsersDM extends DataMapper
{
    public function __construct($adapter)
    {
        $this->adapter = $adapter;
        $this->model = null;
        $this->table_name = 'autopark_users';

        $this->fields = array(
            'user_id',
            'park_id',
        );

        /*$this->_primary_key_update = array('id');
        $this->_primary_key_delete = array('id');*/

    }

}
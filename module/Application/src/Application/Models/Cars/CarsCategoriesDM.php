<?php

namespace Application\Models\Cars;

use Application\Models\DataMapper;

class CarsCategoriesDM extends DataMapper
{
    public function __construct($adapter)
    {
        $this->adapter = $adapter;
        $this->model = null;
        $this->table_name = 'categories';

        $this->fields = array(
            'make',
        );

        /*$this->_primary_key_update = array('id');
        $this->_primary_key_delete = array('id');*/

    }

}
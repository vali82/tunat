<?php

namespace Application\Models\Cars;

use Application\Models\DataMapper;

class CarsPartsMainDM extends DataMapper {

    public function __construct($adapter)
    {
        $this->adapter = $adapter;
        $this->model = null;
        $this->table_name = 'parts_main_categ';

        $this->fields = array(
            'category',
        );

        /*$this->_primary_key_update = array('id');
        $this->_primary_key_delete = array('id');*/

    }

}
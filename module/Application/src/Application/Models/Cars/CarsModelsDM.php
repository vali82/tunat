<?php

namespace Application\Models\Cars;

use Application\Models\DataMapper;

class CarsModelsDM extends DataMapper {

    public function __construct($adapter)
    {
        $this->adapter = $adapter;
        $this->model = null;
        $this->table_name = 'cars_model';

        $this->fields = array(
            'category_id',
            'car_make',
            'model',
            'year_start',
            'year_end',
            'popularity'
        );

        /*$this->_primary_key_update = array('id');
        $this->_primary_key_delete = array('id');*/

    }

}
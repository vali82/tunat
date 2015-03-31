<?php

namespace Application\Models\Ads;

use Application\Models\DataMapper;

class AdDM extends DataMapper {

    public function __construct($adapter)
    {
        $this->adapter = $adapter;
        $this->model = new Ad();
        $this->table_name = 'ads';

        $this->fields = array(
            'park_id',
            'car_category',
            'car_make',
            'car_model',
            'year_start',
            'year_end',
            'part_name',
            'description',
            'price',
            'currency',
            'dateadd',
            'status',
            'updated_at',
            'images'
        );

        /*$this->_primary_key_update = array('id');
        $this->_primary_key_delete = array('id');*/

    }

}
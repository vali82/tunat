<?php

namespace Application\Models\Ads;

use Application\Models\DataMapper;

class AdDM extends DataMapper {

    public function __construct($adapter)
    {
        $this->adapter = $adapter;
        $this->model = null;
        $this->table_name = 'ads';

        $this->fields = array(
            'user_id',
            'part_categ',
            'part_name',
            'car_make',
            'car_model',
            'car_carburant',
            'car_cilindree',
            'description',
            'price',
            'dateadd',
            'status',
            'updated_at'
        );

        /*$this->_primary_key_update = array('id');
        $this->_primary_key_delete = array('id');*/

    }

}
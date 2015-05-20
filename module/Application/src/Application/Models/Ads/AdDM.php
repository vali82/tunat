<?php

namespace Application\Models\Ads;

use Application\Models\DataMapper;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Paginator\Adapter as PaginatorAdapter;
use Zend\Paginator as Paginator;

class AdDM extends DataMapper {

    public function __construct($adapter)
    {
        $this->adapter = $adapter;
        $this->model = new Ad();
        $this->table_name = 'ads';

        $this->fields = array(
            'advertiser_id',
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
            'images',
            'views',
            'contact_displayed',
            'expiration_date',
            'stare',
            'code_oem'
        );

        /*$this->_primary_key_update = array('id');
        $this->_primary_key_delete = array('id');*/

    }
}
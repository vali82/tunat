<?php

namespace Application\Models\Advertiser;

use Application\Models\DataMapper;

class AdvertiserDM extends DataMapper
{
    public function __construct($adapter)
    {
        $this->adapter = $adapter;
        $this->model = new Advertiser();
        $this->table_name = 'advertiser';

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

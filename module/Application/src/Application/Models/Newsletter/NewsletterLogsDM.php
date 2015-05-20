<?php

namespace Application\Models\Newsletter;

use Application\Models\DataMapper;

class NewsletterLogsDM extends DataMapper
{

    public function __construct($adapter)
    {
        $this->adapter = $adapter;
        $this->model = new NewsletterLogs();
        $this->table_name = 'newsletter_logs';

        $this->fields = array(
            'advertiser_id',
            'email_type',
            'dateadd',
        );

        /*$this->_primary_key_update = array('id');
        $this->_primary_key_delete = array('id');*/

    }
}
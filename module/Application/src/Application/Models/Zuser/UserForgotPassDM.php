<?php

namespace Application\Models\Zuser;

use Application\Models\DataMapper;
use Zend\Form\Element\DateTime;

class UserForgotPassDM extends DataMapper
{
    public function __construct($adapter)
    {
        $this->adapter = $adapter;
        $this->model = new UserForgotPass();
        $this->table_name = 'user_forgot_pass';

        $this->fields = array(
            'email',
            'hash',
        );

        /*$this->_primary_key_update = array('id');
        $this->_primary_key_delete = array('id');*/

    }
}

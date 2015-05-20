<?php

namespace Application\Models\Advertiser;

use Application\Models\DataMapper;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;

class AdvertiserUsersDM extends DataMapper
{
    public function __construct($adapter)
    {
        $this->adapter = $adapter;
        $this->model = null;
        $this->table_name = 'advertiser_users';

        $this->fields = array(
            'user_id',
            'advertiser_id',
        );

        /*$this->_primary_key_update = array('id');
        $this->_primary_key_delete = array('id');*/

    }

    public function createFromArray($user_id, $advertiser_id)
    {
        $insert = new Insert($this->table_name);

        $values = array(
            'user_id' => $user_id,
            'advertiser_id' => $advertiser_id
        );
        $insert->values($values);
        $sql = new Sql($this->adapter);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $results = $statement->execute();

        return $results->getGeneratedValue();
    }
}

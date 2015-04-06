<?php

namespace Application\Models\Autoparks;

use Application\Models\DataMapper;
use Zend\Db\Sql\Insert;

class ParkUsersDM extends DataMapper
{
    public function __construct($adapter)
    {
        $this->adapter = $adapter;
        $this->model = null;
        $this->table_name = 'autopark_users';

        $this->fields = array(
            'user_id',
            'park_id',
        );

        /*$this->_primary_key_update = array('id');
        $this->_primary_key_delete = array('id');*/

    }

    public function createFromArray($user_id, $park_id)
    {
        $insert = new Insert($this->table_name);

        $values = array(
            'user_id' => $user_id,
            'park_id' => $park_id
        );
        $insert->values($values);
        $sql = new Sql($this->adapter);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $results = $statement->execute();

        return $results->getGeneratedValue();
    }


}
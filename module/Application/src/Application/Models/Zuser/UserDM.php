<?php

namespace Application\Models\Zuser;

use Application\Models;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Paginator\Adapter as PaginatorAdapter;
use Zend\Paginator as Paginator;

use ZfcUser\Mapper\User as ZfcUserMapper;
use Zend\Crypt\Password\Bcrypt;
use ZfcUser\Options\ModuleOptions as ZfcUserModuleOptions;

class UserDM extends ZfcUserMapper
{
    protected $roleMapper = null;
    protected $_adapter;
    protected $_table_name = 'user';
    protected $_fields = array(
//        'id',
        'email',
        'password',
        'state',
        'hash_login'
    );

    public function __construct($adapter)
    {
        $this->_adapter = $adapter;
    }

    public function setDbAdapter(Adapter $adapter)
    {
        $this->_adapter = $adapter;
    }

    protected function getTableGateway()
    {
        $hydrator = new \Zend\Stdlib\Hydrator\ClassMethods;
        $rowObjectPrototype = new User();
        $resultSet = new \Zend\Db\ResultSet\HydratingResultSet($hydrator, $rowObjectPrototype);
        $tableGateway = new \Zend\Db\TableGateway\TableGateway($this->_table_name, $this->_adapter, null, $resultSet);
        return $tableGateway;
    }

    /**
     * @param string $hash
     * @return Models\User|null
     */
    public function findByHashLogin($hash)
    {
        $results = $this->getTableGateway()->select(array('hash_login' => (string)$hash));
        if (count($results) == 0) {
            return null;
        } elseif (count($results) > 1) {
            return null;
        } else {
            return $results->current();
        }
    }

    /*public function createRow(User $element)
    {
        $insert = new \Zend\Db\Sql\Insert($this->_table_name);

        $values = array();
        foreach ($this->_fields as $field) {
            $fieldMethod = 'get';
            $x = explode('_', $field);
            foreach ($x as $y) {
                $fieldMethod .= ucfirst($y);
            }
            if (method_exists($element, $fieldMethod)) {
                if ($field == 'password') {
                    $values[$field] = $this->encryptPassword($element->getPassword());
                } else {
                    $values[$field] = $element->$fieldMethod();
                }

            } else {
                $values[$field] = '';
            }
        }
        $insert->values($values);

        $sql = new Sql($this->_adapter);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $results = $statement->execute();
        $id = $results->getGeneratedValue();
        return $id;
    }*/

    public function updateRow(User $element, $pass_modified = '')
    {
        $update = new \Zend\Db\Sql\Update($this->_table_name);

        $values = array();
        foreach ($this->_fields as $field) {
            $fieldMethod = 'get';
            $x = explode('_', $field);
            foreach ($x as $y) {
                $fieldMethod .= ucfirst($y);
            }
            /*if ($fieldMethod == 'getUserId') {
                $fieldMethod = 'getId';
            }*/
            if (method_exists($element, $fieldMethod)) {
                if ($field == 'password') {
                    if ($pass_modified != '') {
                        //if ($pass_modified !== 'facebookToLocalUser') {
                        $values[$field] = $this->encryptPassword($pass_modified);
                        //}
                    } else {
                        $values[$field] = $element->$fieldMethod();
                    }
                } else {
                    $values[$field] = $element->$fieldMethod();
                }
            } /*else {
                $values[$field] = '';
            }*/
        }


        $update->set($values)->where(array('user_id' => $element->getId()));
        $sql = new Sql($this->_adapter);
        $statement = $sql->prepareStatementForSqlObject($update);
        return $statement->execute();
    }


    public function deleteUser($id)
    {
        $deleteUsersProvider = new \Zend\Db\Sql\Delete('user_provider');

        $deleteUsersProvider->where('user_id = ' . $id);
        $sql = new Sql($this->_adapter);
        $statement = $sql->prepareStatementForSqlObject($deleteUsersProvider);
        $statement->execute();

        $deleteUsersRL = new \Zend\Db\Sql\Delete('user_role_linker');
        $deleteUsersRL->where('user_id = ' . $id);
        $sql = new Sql($this->_adapter);
        $statement = $sql->prepareStatementForSqlObject($deleteUsersRL);
        $statement->execute();

        $deleteUsers = new \Zend\Db\Sql\Delete($this->_table_name);
        $deleteUsers->where('user_id = ' . $id);
        $sql = new Sql($this->_adapter);
        $statement = $sql->prepareStatementForSqlObject($deleteUsers);
        return $statement->execute();

    }



    private function getZfcModuleOptions()
    {
        return new ZfcUserModuleOptions();
    }

    public function verifyPass($oldPass, $newPass, $currentUser)
    {
        $bcrypt = new Bcrypt();
        $bcrypt->setCost($this->getZfcModuleOptions()->getPasswordCost());

        if (!$bcrypt->verify($oldPass, $currentUser->getPassword())) {

            return false;

        } else {
            //$pass = $bcrypt->create($newPass);
            //$currentUser->setPassword($pass);

            return true;

        }
    }

    public function encryptPassword($password)
    {
        $bcrypt = new Bcrypt;
        $bcrypt->setCost($this->getZfcModuleOptions()->getPasswordCost());
        return $bcrypt->create($password);
    }
}

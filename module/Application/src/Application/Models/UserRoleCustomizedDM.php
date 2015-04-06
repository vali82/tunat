<?php
namespace Application\Models;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Paginator\Adapter as PaginatorAdapter;
use Zend\Paginator as Paginator;


class UserRoleCustomizedDM {
	protected $roleMapper = null;
    protected $_adapter;
    protected $_table_name = 'user_role_linker';
    protected $_fields = array(
    	'user_id',
    	'role_id',
    );

    public function __construct($adapter)
    {
    	$this->_adapter = $adapter;
    }
    
    public function setDbAdapter(Adapter $adapter)
    {
        $this->_adapter = $adapter;
    }
    
    public function createRow($element) {
    	$insert = new \Zend\Db\Sql\Insert($this->_table_name);
    
    	$insert->values($element);
    
    	$sql = new Sql($this->_adapter);
    	$statement = $sql->prepareStatementForSqlObject($insert);
    	$results = $statement->execute();
    	$id = $results->getGeneratedValue();
    	return $id;
    }

	public function updateRow($user_id, $role_id)
	{
		$update = new \Zend\Db\Sql\Update($this->_table_name);

		/*$values = array();
		foreach ($this->_fields as $field) {
			$fieldMethod = 'get';
			$x = explode('_', $field);
			foreach ($x as $y) {
				$fieldMethod .= ucfirst($y);
			}
			if (method_exists($element, $fieldMethod)) {
				$values[$field] = $element->$fieldMethod();
			} else {
				$values[$field] = '';
			}
		}*/

		$update->set(array('role_id'=>$role_id))->where(array('user_id' => $user_id));
		$sql = new Sql($this->_adapter);
		$statement = $sql->prepareStatementForSqlObject($update);
		return $statement->execute();
	}


	/**
	 * @param int $uid
	 *
	 * @return object|null
	*/
    public function fetchByUserId($uid) {
    	
    	
		$sql = new Sql($this->_adapter);
		$select = $sql->select();
		$select->from($this->_table_name);
		$select->where(array('user_id' => $uid));
		
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute(); 
		
		if (count($results)==0) {
			return null;
		} elseif (count($results)>1) {
			return null;
		} else {
			return $results->current();
		}
    }
    public function fetchRoleByUserId($id) {

        $sql = new Sql($this->_adapter);
        $select = $sql->select();
        $select->from($this->_table_name);
            $select
                ->join(array('ur' => 'user_role'), 'ur.id = user_role_linker.role_id',array("entity_type"=>"role_id"))
                ->where('user_id = '.(int)$id.'');

        $statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();

        if (count($results)==0) {
            return null;
        } elseif (count($results)>1) {
            return null;
        } else {
            return $results->current();
        }

    }
}
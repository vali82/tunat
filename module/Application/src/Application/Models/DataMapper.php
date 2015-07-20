<?php
namespace Application\Models;

use Application\ActionLog\ActionLogDataMapper;
use Application\Libs\General;
use Kindergartens\Alerts\AlertsDM;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Update;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter as PaginatorAdapter;
use Zend\Paginator as Paginator;

abstract class DataMapper implements MMDataMapperInterface
{
    /**@var \Zend\Db\Adapter\AdapterInterface */
    protected $adapter;
    /**@var string table name */
    protected $table_name = '';
    /**@var array of table columns */
    protected $fields = [];
    /**@var object */
    protected $model = null;
    /**@var array of columns for update row */
    protected $primary_key_update = ['id'];

    protected $paginate_values = null;

    protected $allow_log_action = false;
    protected $log_action_interested_fields = null;

    protected $columns = [];
    protected $joins = [];

    public function getTableName()
    {
        return $this->table_name;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getAllowLogAction()
    {
        return $this->allow_log_action;
    }

    public function setPaginateValues($sw)
    {
        $this->paginate_values = $sw;
    }

    public function setColumns($col)
    {
        $this->columns = $col;
    }

    public function setJoins($joins)
    {
        $this->joins = $joins;
    }

    /**
     * @return TableGateway
     */
    protected function getTableGateway()
    {
        $hydrator = new ClassMethods;
        $rowObjectPrototype = $this->model;
        $resultSet = new HydratingResultSet($hydrator, $rowObjectPrototype);
        $tableGateway = new TableGateway($this->table_name, $this->adapter, null, $resultSet);
        return $tableGateway;
    }

    private function save2log($element, $id, $type)
    {
        if ($this->allow_log_action && $id) {
            $logAction = General::getFromSession('logAction');
            if ($logAction !== null) {
                $logActionDM = new ActionLogDataMapper($this->adapter);
                $logActionDM->setUserId($logAction['user_id']);
                $logActionDM->setKindergartenId($logAction['kindergarten_id']);
                $logActionDM->setController($logAction['controller'], $logAction['action']);

                $notes = null;
                if ($this->log_action_interested_fields !== null) {
                    $notes = array();
                    foreach ($this->log_action_interested_fields as $field) {
                        $fieldMethod = 'get';
                        $x = explode('_', $field);
                        foreach ($x as $y) {
                            $fieldMethod .= ucfirst($y);
                        }
                        if (method_exists($element, $fieldMethod)) {
                            $notes[] = $field . ': ' . $element->$fieldMethod();
                        }
                    }
                    if (count($notes) > 0) {
                        $notes = 'Campuri de interes: ' . "\r\n- " . implode("\r\n- ", $notes);
                    } else {
                        $notes = null;
                    }
                }

                $logActionDM->logActionDB($type, $this->table_name, $id, $notes);
            }
        }
    }

    /**
     * @param $select \Zend\Db\Sql\Select
     * @param string
     * @param string
     *
     * @return \Zend\Db\Sql\Select
     */
    protected function constructWhere($select, $key, $value)
    {
        if (strpos($value, '__expression[:]') !== false) {
            $x = explode('[:]', $value);
            switch ($x[1]) {
                case "between":
                    $select
                        ->where->between($key, $x[2], $x[3]);
                    break;
                case "in":
                    $select
                        ->where->in($key, explode('[::]', $x[2]));
                    break;
                case "expression":
                    $select->where($x[2]);
                    break;
                default:
                    break;
            }
        } elseif ($value !== false) {
            $select->where($key . ' = ' . (is_string($value) ? '"' . $value . '"' : (int)$value) . '');
        }
        return $select;
    }

    public static function between($min, $max)
    {
        return '__expression[:]between[:]' . $min . '[:]' . $max;
    }
    public static function in($listOfAccepted)
    {
        return '__expression[:]in[:]' . implode('[::]', $listOfAccepted);
    }
    /**
     * sunt acceptate deocamdata expresii de genul: [col] > [value], [col] = [value], [col] <> [value]
     * @param string $value
     *
     * @return string
     */
    public static function expression($value)
    {
        return '__expression[:]expression[:]' . $value;
    }

    public function createRow($element)
    {
        $insert = new Insert($this->table_name);

        $values = array();
        foreach ($this->fields as $field) {
            $fieldMethod = 'get';
            $x = explode('_', $field);
            foreach ($x as $y) {
                $fieldMethod .= ucfirst($y);
            }
            if (method_exists($element, $fieldMethod)) {
                if ($field == 'dateadd' && ($element->getDateadd() === '' || $element->getDateadd() === null)) {
                    $element->setDateadd(date('Y-m-d H:i:s'));
                } elseif ($field == 'updated_at' &&
                    ($element->getUpdatedAt() === '' || $element->getUpdatedAt() === null)) {
                    $element->setUpdatedAt("0000-00-00 00:00:00");
                }
                $values[$field] = $element->$fieldMethod();
            } else {
                $values[$field] = '';
            }
        }
        $insert->values($values);

        $sql = new Sql($this->adapter);
        try {
            $statement = $sql->prepareStatementForSqlObject($insert);
            $results = $statement->execute();
            $id = $results->getGeneratedValue();

            if (!$id) {
                $id4Log = array();
                foreach ($this->primary_key_update as $key) {
                    $methodName = 'get';
                    $x = explode('_', $key);
                    foreach ($x as $y) {
                        $methodName .= ucfirst($y);
                    }
                    if (method_exists($element, $methodName)) {
                        $id4Log[] = $element->$methodName();
                    }
                }
                $id = implode('-', $id4Log);
            }

            $this->save2log($element, $id, 'create');

            return $id;
        } catch (\Exception $e) {
            return null;
        }

    }

    public function updateRow($element)
    {
        $update = new Update($this->table_name);

        $values = array();
        foreach ($this->fields as $field) {
            $fieldMethod = 'get';
            $x = explode('_', $field);
            foreach ($x as $y) {
                $fieldMethod .= ucfirst($y);
            }
            if (method_exists($element, $fieldMethod)) {
                if ($field == 'updated_at' &&
                    ($element->getUpdatedAt() === '0000-00-00 00:00:00' || $element->getUpdatedAt() === null)) {
                    $element->setUpdatedAt(date('Y-m-d H:i:s'));
                }
                $values[$field] = $element->$fieldMethod();
            } else {
                $values[$field] = '';
            }
        }
        $where = array();
        $id4Log = array();

        if (is_array($this->primary_key_update)) {
            foreach ($this->primary_key_update as $key) {
                $methodName = 'get';
                $x = explode('_', $key);
                foreach ($x as $y) {
                    $methodName .= ucfirst($y);
                }
                if (method_exists($element, $methodName)) {
                    $where[$key] = $element->$methodName();
                    $id4Log[] = $element->$methodName();
                }
            }
        }

        if (count($where) > 0) {
            $update->set($values)->where($where);
            $sql = new Sql($this->adapter);
            $statement = $sql->prepareStatementForSqlObject($update);
            $statement->execute();

            $this->save2log($element, implode('-', $id4Log), 'update');

            return true;
        } else {
            return false;
        }
    }

    public function deleteOne($element)
    {
        $delete = new Delete($this->table_name);

        $id4Log = null;

        $where = array();
        if (is_array($this->primary_key_update)) {
            foreach ($this->primary_key_update as $key) {
                $methodName = 'get';
                $x = explode('_', $key);
                foreach ($x as $y) {
                    $methodName .= ucfirst($y);
                }
                if (method_exists($element, $methodName)) {
                    $where[$key] = $element->$methodName();
                    $id4Log = $element->$methodName();
                }
            }
        }
        if (count($where) > 0) {
            $this->save2log($element, $id4Log, 'delete');

            $delete->where($where);
            $sql = new Sql($this->adapter);
            $statement = $sql->prepareStatementForSqlObject($delete);
            return $statement->execute();
        } else {
            return false;
        }
    }

    public function deleteRows($where)
    {
        $delete = new Delete($this->table_name);

        if (is_array($where)) {
            $delete->where($where);
            $sql = new Sql($this->adapter);
            $statement = $sql->prepareStatementForSqlObject($delete);
            $result = $statement->execute();
            return $result;
        }
        return false;
    }

    /**
     * fetch by primaryKey
     *
     * @param array|int $selectKey
     *
     * @return object|null
     */
    public function fetchOne($selectKey)
    {
        $results = $this->getTableGateway()->select(function (Select $select) use ($selectKey) {

            if (is_array($selectKey)) {
                foreach ($selectKey as $k => $pkey) {
                    $select = $this->constructWhere($select, $k, $pkey);
                }
            } else {
                $select->where($this->primary_key_update[0] . ' = ' . (int)$selectKey . '');
            }
        });
//		var_dump($results->getDataSource()->getResource()->queryString);
        if (count($results) == 1) {
            return $results->current();
        }
        return null;
    }

    /**
     * fetch all
     *
     * @param array $selectKey
     * <p>
     * array of where conditions
     * </p>
     * @param null|array $orderBy
     * <p>
     * array of order by if needed
     * </p>
     * @param $limit null|array
     * <p>
     * key0: pagina
     * key1: limit pe page...
     * [5,10] ... pagina 5 10 pe pagina
     * </p>
     *
     * @return $this->_model[]|null
     */
    public function fetchAllDefault($selectKey, $orderBy = null, $limit = null, $groupBy = null)
    {
        $results = $this->getTableGateway()->select(function (Select $select) use (
            $selectKey,
            $orderBy,
            $limit,
            $groupBy
        ) {

            if (count($this->columns) > 0) {
                $select->columns($this->columns);
            }

            if (count($this->joins) > 0) {
                foreach ($this->joins as $join) {
                    $select->join(
                        $join['name'],
                        $join['on'],
                        $join['columns'],
                        $join['type']
                    );
                }
            }

            if (is_array($selectKey)) {
                foreach ($selectKey as $k => $pkey) {
                    $select = $this->constructWhere($select, $k, $pkey);
                }
            } else {
                $select
                    ->where('1=2');
            }

            if ($orderBy !== null) {
                $select->order($orderBy);
            }
            if ($limit !== null) {
                $select->limit($limit[1])->offset(((int)$limit[0]-1)*$limit[1]);
            }

            if ($groupBy !== null) {
                $select->group($groupBy);
            }


        });
//		General::echop($results->getDataSource()->getResource()->queryString);

        if (count($results) == 0) {
            $res = null;
        } else {
            $res = array();
            foreach ($results as $r) {
                array_push($res, $r);
            }
        }

        if ($this->paginate_values !== null) {
            $page = $this->paginate_values['page'];
            $res = ($res == null ? array() : $res);


            $adapter = new Paginator\Adapter\ArrayAdapter($res);
            $paginator = new Paginator\Paginator($adapter);


            $paginator->setItemCountPerPage(
                isset($this->paginate_values['items_per_page']) ? $this->paginate_values['items_per_page'] : 10
            );
            $paginator->setCurrentPageNumber($page);

            return $paginator;

        } else {
            return $res;

        }

    }

    /**
     * @param array <p>of where conditions</p>
     *
     * @return int
     */
    public function countResults($selectKey = null)
    {
        $sql = new Sql($this->adapter);
        $select = $sql->select();
        if (is_array($selectKey)) {
            foreach ($selectKey as $k => $pkey) {
                $select = $this->constructWhere($select, $k, $pkey);
            }
        }
        /*if ($where != '') {
            $select = $sql->select()->where($where);
        } else {
            $select = $sql->select();
        }*/

        $countSelect = $select
            ->from($this->table_name)
            ->columns(array(
                'COUNT' => new Expression('COUNT(*)')
            ));
        $statement = $sql->prepareStatementForSqlObject($countSelect);
        $result = $statement->execute();
//        General::echop( $select->getSqlString($this->adapter->getPlatform()));
        $row = $result->current();

        return $row['COUNT'];
    }

    public function fetchLast($where, $order = null)
    {
        $results = $this->getTableGateway()->select(function (Select $select) use ($where, $order) {

            if (is_array($where)) {
                foreach ($where as $k => $pkey) {
                    $select = $this->constructWhere($select, $k, $pkey);
                }
            } else {
                $select->where($this->primary_key_update[0] . ' = ' . (int)$where . '');
            }

            if ($order === null) {
                $order = [];
                foreach ($this->primary_key_update as $col) {
                    $order[$col] = 'DESC';
                }
            }
            $select->order($order)->limit(1);

        });
//		var_dump($results->getDataSource()->getResource()->queryString);
        if (count($results) == 1) {
            return $results->current();
        }
        return null;
    }

    public function fetchResultsArray($selectKey = null)
    {
        $sql = new Sql($this->adapter);
        $select = $sql->select();
        if (is_array($selectKey)) {
            foreach ($selectKey as $k => $pkey) {
                $select = $this->constructWhere($select, $k, $pkey);
            }
        }
        /*if ($where != '') {
            $select = $sql->select()->where($where);
        } else {
            $select = $sql->select();
        }*/

        $countSelect = $select
            ->from($this->table_name)
        ;

        $statement = $sql->prepareStatementForSqlObject($countSelect);
        $result = $statement->execute();
        $return = [];
        $id = count($this->primary_key_update) == 1 ? $this->primary_key_update[0] : null;
        $cnt = 0;
        foreach($result as $r) {
            $return[($id !== null ? $r[$id] : $cnt)] = $r;
            $cnt++;
        }

        return $return;
    }

}

<?php

namespace Application\Forms;

use Application\Libs\General;

use Zend\Form\Form;
use Zend\Form\FormInterface;

class AbstractForm extends Form
{
	protected $_cancel_route = null;
    protected $_db_adapter = null;


    //protected $_eavEntity = null;
//    /** @var $_eav \Kindergartens\Eav\Eav*/
    /*protected $_eavModel;
    protected $_eavValues = array();
    protected $_eavEntityObj = null;
    protected $_eavOldValues = array();*/

	
    public function __construct($name = null)
    {
        parent::__construct($name);
    }
    
    public function setCancelRoute($route) {
    	$this->_cancel_route = $route;
    }

    /*public function setEavEntity($entity_type, $eavModel, $entityObj = null)
    {
        $this->_eavEntity = $entity_type;
        $this->_eavModel = $eavModel;

        $this->_eavEntityObj = ($entityObj!== null && $this->_eavModel->getEntityId($entityObj) !== null) ? $entityObj : null;

        if ($this->_eavEntityObj !== null) {
            $this->_eavOldValues = $this->_eavModel->getValues4specificEntity($this->_eavEntity, $this->_eavEntityObj);
        }
    }
    

    protected function addEavFields()
    {

        $eav_attributes = General::getFromSession('eav_attributes');

        $old_values = $this->_eavOldValues;

        foreach ($eav_attributes as $attr) {

            if ($attr['entity_type'] == $this->_eavEntity &&
                (
                    $attr['disabled_date'] == null || // atributul este activ
                    isset($old_values['eav_'.$attr['attribute_name']])  // sau are completata valoare inainte de a fi disabled
                )
            ) {
                switch ($attr['attr_form_type']) {
	                case "float":
	                case "int" :
	                case "string" : $type = "text";
                        break;
	                case "text" : $type = "textarea";
		                break;
	                case "checkbox" : $type = "checkbox";
		                break;
	                case "select" : $type = "select";
						break;
	                default :
                        $type = "text";
                        break;
                }
                $this->add(array(
	                'type' => $type,
                    'name' => 'eav_'.$attr['attribute_name'],
                    'options' => array(
                        'label' => $attr['label'],
	                    'options' => ($type == 'select' ? array_combine(explode("\r\n",$attr['options']), explode("\r\n",$attr['options'])) : null)
                    ),
                    'attributes' => array(
	                    'id' => 'eav_'.$attr['attribute_name'],
	                    'extraInfo' => $attr['description']
                    ),
                ));
            }
        }

    }

    public function addEavFilters()
    {
        $eav_attributes = General::getFromSession('eav_attributes');

        $old_values = $this->_eavOldValues;

        $filters = [];
        foreach ($eav_attributes as $attr) {
            if ($attr['entity_type'] == $this->_eavEntity &&
                (
                    $attr['disabled_date'] == null || // atributul este activ
                    isset($old_values['eav_'.$attr['attribute_name']])  // sau are completata valoare inainte de a fi disabled
                )
            ) {
                switch ($attr['attr_form_type']) {
                    case "string" :
                        $filters[] = [
                            'name' => 'eav_'.$attr['attribute_name'],
                            'required'   => ($attr['required'] ? true : false),
                            'validators' => [
                                [
                                    'name'    => 'StringLength',
                                    'options' => [
                                        'max' => 250
                                    ],
                                ],
                            ],
                            'filters'   => [
                                ['name' => 'StringTrim'],
                                ['name' => 'StripTags'],
                            ],
                        ];
                        break;
	                case "text" :
		                $filters[] = [
			                'name' => 'eav_'.$attr['attribute_name'],
			                'required'   => ($attr['required'] ? true : false),
			                'validators' => [
				                [
					                'name'    => 'StringLength',
					                'options' => [
						                'max' => 65000
					                ],
				                ],
			                ],
			                'filters'   => [
				                ['name' => 'StringTrim'],
				                ['name' => 'StripTags'],
			                ],
		                ];
		                break;
	                case "checkbox" :
		                $filters[] = [
			                'name' => 'eav_'.$attr['attribute_name'],
			                'required'   => ($attr['required'] ? true : false),
			                'validators' => [
				                [
					                'name'    => 'Int',
					                'options' => [
						                'max' => 1
					                ],
				                ],
			                ],
			                'filters'   => [
				                ['name' => 'StringTrim'],
				                ['name' => 'StripTags'],
			                ],
		                ];
		                break;
	                case "int" :
		                $filters[] = [
			                'name' => 'eav_'.$attr['attribute_name'],
			                'required'   => ($attr['required'] ? true : false),
			                'validators' => [
				                [
					                'name'    => 'Int',
					                'options' => [
						                'min' => 0
					                ],
				                ],
			                ],
			                'filters'   => [
				                ['name' => 'StringTrim'],
				                ['name' => 'StripTags'],
			                ],
		                ];
		                break;
	                case "float" :
		                $filters[] = [
			                'name' => 'eav_'.$attr['attribute_name'],
			                'required'   => ($attr['required'] ? true : false),
			                'validators' => [
				                [
					                'name'    => 'Float',
					                'options' => [
						                'min' => 0
					                ],
				                ],
			                ],
			                'filters'   => [
				                ['name' => 'StringTrim'],
				                ['name' => 'StripTags'],
			                ],
		                ];
		                break;
	                case "select" :
		                $filters[] = [
			                'name' => 'eav_'.$attr['attribute_name'],
			                'required'   => ($attr['required'] ? true : false),
			                'validators' => [
				                [
					                'name'    => 'StringLength',
					                'options' => [
						                'max' => 250
					                ],
				                ],
			                ],
			                'filters'   => [
				                ['name' => 'StringTrim'],
				                ['name' => 'StripTags'],
			                ],
		                ];
		                break;

	                default :

                        break;
                }

            }
        }
        return $filters;
    }*/

    public function bind($entityObj, $flags = FormInterface::VALUES_NORMALIZED)
    {
//        if ($this->_eavEntity !== null) {
//            $old_values = $this->_eavOldValues;
//            //$old_values = $this->_eavModel->getValues4specificEntity($this->_eavEntity, $entityObj);  // EAV
//	        //General::echop($old_values);
//	        $this->populateValues($old_values);
//            //$this->bindValues($old_values);
//        }
        parent::bind($entityObj, $flags);
    }

    /*public function saveEavValues($entityObj)
    {
        $arrayValues = $this->getData(FormInterface::VALUES_AS_ARRAY);
        foreach($arrayValues as $field=>$value) {
            if (strpos($field,'eav_') !== FALSE) {
                if ($value != '') {
                    $this->_eavModel->setAttributeValue($entityObj, str_replace('eav_','',$field), $value);
                } else {
                    $this->_eavModel->deleteAttribute($entityObj, str_replace('eav_','',$field));
                }
            }
        }
    }*/




    //IF YOU WILL WORK WITH DATABASE 
    //AND NEED bind() FORM FOR EDIT DATA, YOU NEED OVERRIDE
    //populateValues() FUNC LIKE THIS
    public function populateValues($data)
    {
        //$eav_values = $this->_eavModel->getValues4allAttributes($this->_eavEntity, $entityObj);  // EAV
        foreach($data as $key=>$row)
        {
           if (is_array(@json_decode($row))){
                $data[$key] =   new \ArrayObject(\Zend\Json\Json::decode($row), \ArrayObject::ARRAY_AS_PROPS);
           }
        }
         
        parent::populateValues($data);
    }
}

<?php

namespace Application\Forms\Filters;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
 
class AbstractFilter implements InputFilterAwareInterface
{
	protected $_db_adapter;
	protected $_translator;
    protected $inputFilter;
    protected $_filterFields;
    protected $_eavFilterFields;

    public function setDbAdapter($dbadapter) {
    	$this->_db_adapter = $dbadapter;
    }
    
    public function setTranslator($translator) {
    	$this->_translator = $translator;
    }
 
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    /*public function setEavFilters($eavFilterFields)
    {
        $this->_eavFilterFields = $eavFilterFields;
    }*/



    /**
     * Retrieve input filter
     *
     * @return InputFilterInterface
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            foreach($this->_filterFields as $filter) {
                $inputFilter->add($filter);
            }

	        /*if ($this->_eavFilterFields) {
	            foreach($this->_eavFilterFields as $filter) {
	                $inputFilter->add($filter);
	            }
	        }*/

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}

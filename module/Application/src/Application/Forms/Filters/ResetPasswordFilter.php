<?php
namespace Application\Forms\Filters;
 
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
 
class ResetPasswordFilter implements InputFilterAwareInterface
{
	//public $firstname;

	protected $_db_adapter;
	protected $_translator;
	//public $selected_parent;

	protected $inputFilter;

	public function setDbAdapter($dbadapter) {
		$this->_db_adapter = $dbadapter;
	}

	public function setTranslator($translator) {
		$this->_translator = $translator;
	}

	/* public function exchangeArray($data)
	{
		$this->password = (isset($data['password'])) ? $data['password'] : null;
	}

	public function getArrayCopy()
	{
		return get_object_vars($this);
	}  */

	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("Not used");
	}
 
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();


	        $inputFilter->add(array(
		        'name'       => 'email',
		        'required'   => true,
		        'validators' => array(
			        array(
				        'name'    => 'StringLength',
			        ),
			        array('name'=>'EmailAddress'),
		        ),
		        'filters'   => array(
			        array('name' => 'StringTrim'),
		        ),
	        ));

	        $inputFilter->add(array(
		        'name'       => 'password_retype',
		        'required'   => true,
		        'filters'    => array(array('name' => 'StringTrim')),
		        'validators' => array(
			        array(
				        'name'    => 'StringLength',
				        'options' => array(
					        'min' => 6,
				        ),
			        ),
			        array(
				        'name'    => 'Identical',
				        'options' => array(
					        'token' => 'password',
				        ),
			        ),
		        ),
	        ));

 
            $this->inputFilter = $inputFilter;
        }
 
        return $this->inputFilter;
    }
}
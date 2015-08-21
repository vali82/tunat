<?php
namespace Application\Forms\Filters;
 
use Zend\InputFilter\FileInput;
use Zend\Validator\File\Extension;
use Zend\Validator\File\Size;
use Zend\Validator\File\UploadFile;

class ContactFilter extends AbstractFilter
{

	public function __construct() {

		$this->_filterFields = array(
			array(
				'name'       => 'name',
				'required'   => true,
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'min' => 2,
                            'max' => 50,
						),
					),
				),
				'filters'   => array(
					array('name' => 'StringTrim'),
					array('name' => 'StripTags'),
				),
			),
            array(
                'name'       => 'email',
                'required'   => true,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'min' => 2,
                            'max' => 50,
                        ),
                    ),
                    [
                        'name' => 'EmailAddress'
                    ]
                ),
                'filters'   => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
            ),
            array(
                'name'       => 'subject',
                'required'   => true,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'min' => 2,
                            'max' => 50,
                        ),
                    ),
                ),
                'filters'   => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
            ),
            array(
                'name'       => 'message',
                'required'   => true,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'min' => 5,
                            'max' => 2000,
                        ),
                    ),
                ),
                'filters'   => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
            ),

		);
	}
}
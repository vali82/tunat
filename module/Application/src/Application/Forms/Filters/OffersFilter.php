<?php
namespace Application\Forms\Filters;
 
use Zend\InputFilter\FileInput;
use Zend\Validator\File\Extension;
use Zend\Validator\File\Size;
use Zend\Validator\File\UploadFile;

class OffersFilter extends AbstractFilter
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
                'name'       => 'phone',
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
                'name' => 'state',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'Int',
                        'options' => array(
                            'min' => 1,
                            'max' => 50,
                        ),
                    ),
                ),
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
            ),

		);
	}
}
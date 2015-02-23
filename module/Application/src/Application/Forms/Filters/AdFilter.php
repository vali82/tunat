<?php
namespace Application\Forms\Filters;
 
use Zend\InputFilter\FileInput;
use Zend\Validator\File\Extension;
use Zend\Validator\File\Size;
use Zend\Validator\File\UploadFile;

class AdFilter extends AbstractFilter
{

	public function __construct() {

		$this->_filterFields = array(
			array(
				'name'       => 'part_categ',
				'required'   => true,
				'filters'   => array(
					array('name' => 'StringTrim'),
					array('name' => 'StripTags'),
				),
			),
			array(
				'name'       => 'part_name',
				'required'   => false,
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'min' => 5,
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
                'name'       => 'car_make',
                'required'   => true,
                'filters'   => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
            ),
            array(
                'name'       => 'car_model',
                'required'   => true,
                'filters'   => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
            ),
            array(
                'name'       => 'car_carburant',
                'required'   => false,
                'filters'   => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
            ),
            array(
                'name'       => 'car_cilindree',
                'required'   => false,
                'filters'   => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
            ),
            array(
                'name'       => 'description',
                'required'   => true,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'min' => 5,
                            'max' => 1000,
                        ),
                    ),
                ),
                'filters'   => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
            ),
            array(
                'name'       => 'price',
                'required'   => false,
                'filters'   => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
            ),
		);
	}
}
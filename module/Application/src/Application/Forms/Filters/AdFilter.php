<?php
namespace Application\Forms\Filters;

use Zend\InputFilter\FileInput;
use Zend\Validator\File\Extension;
use Zend\Validator\File\Size;
use Zend\Validator\File\UploadFile;

class AdFilter extends AbstractFilter
{
    public function __construct($role)
    {
        $this->_filterFields = array(
            array(
                'name' => 'part_name',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 5,
                            'max' => 50,
                        ),
                    ),
                ),
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
            ),
            array(
                'name' => 'car_category',
                'required' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
            ),
            array(
                'name' => 'car_model',
                'required' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
            ),
            array(
                'name' => 'description',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 5,
                            'max' => 1000,
                        ),
                    ),
                ),
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
            ),
            array(
                'name' => 'price',
                'required' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
                'validators' => [
                    [
                        'name' => 'Float',
                        'locale' => "en_US"
                    ],
                    array(
                        'name'    => 'GreaterThan',
                        'options' =>  array(
                            'min'       => 1,
                            'inclusive' => true
                        )
                    ),
                ]
            ),
        );
        if ($role == 'contentmanager' || $role == 'admin') {
            $this->_filterFields[] = array(
                'name' => 'adv_name',
                'required' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
            );
            $this->_filterFields[] = array(
                'name' => 'adv_email',
                'required' => false,
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
                'validators' => array(
                    array(
                        'name' => 'EmailAddress',
                    ),
                ),
            );
            $this->_filterFields[] = array(
                'name' => 'adv_tel',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 10,
                            'max' => 15,
                        ),
                    ),
                ),
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
            );
            $this->_filterFields[] = array(
                'name' => 'adv_city',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 2,
                            'max' => 50,
                        ),
                    ),
                ),
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
            );
            $this->_filterFields[] = array(
                'name' => 'adv_state',
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
            );
        }
    }
}
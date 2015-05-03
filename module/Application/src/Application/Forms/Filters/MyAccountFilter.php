<?php
namespace Application\Forms\Filters;

use Zend\Validator\File\UploadFile;

class MyAccountFilter extends AbstractFilter
{
    public function __construct()
    {
        $this->_filterFields = array(
            array(
                'name' => 'name',
                'required' => false,
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
                'name' => 'name2',
                'required' => false,
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
                'name' => 'description',
                'required' => false,
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
                'name' => 'tel1',
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
            ),
            array(
                'name' => 'city',
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
            ),
            array(
                'name' => 'state',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'Int',
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
            ),
            array(
                'name' => 'imagefile',
                'required' => false,
                'validators' => array(
                    new UploadFile()
                ),
            )

        );
    }
}
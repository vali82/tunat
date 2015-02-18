<?php
namespace Application\Forms;
//use Kindergartens\Children\ChildRegistration;
//use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class AdForm extends AbstractForm
{
    public function create()
    {
//        $this->setHydrator(new ClassMethodsHydrator(false))
//            ->setObject(new ChildRegistration());

        $this->setAttribute('method', 'post');
        $this->setName('createAd');


        $this->add(array(
            'name' => 'custom_form_spacer',
            'type' => 'hidden',
            'attributes' => array(
                'textAbove' => 'Detalii masina',
            )
        ));

        $this->add(array(
            'name' => 'car_make',
            'options' => array(
                'label' => 'Marca Masina',
            ),
            'attributes' => array(
                'group' => array('size'=> 'col-sm-4','type'=>'start'),
                'type' => 'text',
//				'id' => 'first_name',
                //'required' => true
            ),
        ));

        $this->add(array(
            'name' => 'car_model',
            'type' => 'text',
            'options' => array(
                'label' => 'Model Masina',
            ),
            'attributes' => array(
                'group' => array('size'=> 'col-sm-4','type'=>'end'),
//				'id' => 'last_name',
            ),
        ));

        $this->add(array(
            'name' => 'phone',
            'options' => array(
                'label' => 'Telefon',
            ),
            'attributes' => array(
                'group' => array('size'=> 'col-sm-2','type'=>'start'),
                'type' => 'text',
            ),
        ));

        $this->add(array(
            'name' => 'address',
            'type' => 'text',
            'options' => array(
                'label' => 'Adressa',
            ),
            'attributes' => array(
                'group' => array('size'=> 'col-sm-6','type'=>'end'),
            ),
        ));

        $this->add(array(
            'name' => 'custom_form_spacer',
            'type' => 'hidden',
            'attributes' => array(
                'textAbove' => 'Detalii Copil',
            )
        ));

        $this->add(array(
            'name' => 'firstNameChild',
            'type' => 'text',
            'options' => array(
                'label' => 'Prenume Copil',
            ),
            'attributes' => array(
                'group' => array('size'=> 'col-sm-4','type'=>'start'),
//				'id' => 'last_name',
            ),
        ));

        $this->add(array(
            'name' => 'age',
            'type' => 'text',
            'options' => array(
                'label' => 'Varsta',
            ),
            'attributes' => array(
                'group' => array('size'=> 'col-sm-4','type'=>'end'),
//				'id' => 'last_name',
            ),
        ));

        $this->add(array(
            'name' => 'custom_form_spacer',
            'type' => 'hidden',
            'attributes' => array(
                'textAbove' => '',
            )
        ));

        $this->add(array(
            'name' => 'obs',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Observatii',
            ),
            'attributes' => array(
                //'group' => array('size'=> 'col-sm-4','type'=>'end'),
//				'id' => 'last_name',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Trimite Cererea',
                'id' => 'submitbutton',
                'cancelLink' => $this->_cancel_route
            ),
        ));
    }

}
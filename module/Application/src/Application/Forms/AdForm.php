<?php

namespace Application\Forms;

use Application\libs\General;
use Application\Models\Ads\Ad;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class AdForm extends AbstractForm
{
    public function create($resourceObj, $carMake, $years, $role, $states)
    {
        $this->setHydrator(new ClassMethodsHydrator(true))
            ->setObject($resourceObj);

        $this->setAttribute('method', 'post');
        $this->setName('fileupload');
        $this->setAttribute('id', 'fileupload');


//-------------------------- DETALII MASINA--------------------
        $this->add(array(
            'type' => 'hidden',
            'name' => 'x1',
            'attributes' => array(
                'textAbove' => 'Detalii masina',
                'custom_form_spacer' => true,
            )
        ));

        $this->add(array(
            'type' => 'select',
            'name' => 'car_category',
            'options' => array(
                'label' => 'Piesa Pentru',
                'options' => ['0' => 'Alege Categoria'] + $carMake,
            ),
            'attributes' => array(
                'id' => 'select2CarMake',
//                'group' => array('size' => 'col-sm-8 col-md-4', 'sizeLabel' => 'col-sm-4 col-md-2', 'type' => 'start'),
                'group' => array('size' => 'col-lg-4 col-md-4 col-sm-4 col-xs-12', 'type' => 'start'),
                'required' => true,
                'extraInfo' => 'obligatoriu'

            ),
        ));

        $this->add(array(
            'type' => 'select',
            'name' => 'car_make',
            'options' => array(
                'label' => 'Marca',
                'options' => ['' => 'Marca'],
                //'empty_option' => '--- Selecteaza Parinte ---',
                'disable_inarray_validator' => true
            ),
            'attributes' => array(
//                'noLabel' => true,
//                'disabled' => 'disabled',
                'id' => 'select2CarModels',
                'group' => array('size' => 'col-lg-4 col-md-4 col-sm-4 col-xs-12', 'type' => ''),
                'required' => true,
                'extraInfo' => 'obligatoriu'
            ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'car_model',
            'options' => array(
                'label' => 'Model',
//                'disable_inarray_validator' => true
            ),
            'attributes' => array(
                'placeholder' => 'Model',
//                'disabled' => 'disabled',
//                'noLabel' => true,
                'id' => 'select2CarModels2',
                'group' => array('size' => 'col-lg-4 col-md-4 col-sm-4 col-xs-12', 'type' => 'end'),
                'required' => true,
                'extraInfo' => 'obligatoriu'

            ),
        ));

        for ($i=date('Y'); $i>1960; $i--) {
            $years[$i] = $i;
        }

        $this->add(array(
            'type' => 'select',
            'name' => 'year_start',
            'options' => array(
                'label' => 'An Fabricatie',
                'options' => ['' => 'de la '] + $years,
                //'empty_option' => '--- Selecteaza Parinte ---',
//                'disable_inarray_validator' => true
            ),
            'attributes' => array(
//                'noLabel' => true,
//                'disabled' => 'disabled',
                'id' => 'year_start',
                'group' => array('size' => 'col-lg-2 col-md-2 col-sm-2 col-xs-12', 'type' => 'start'),
                'required' => true,
                'extraInfo' => 'obligatoriu'
            ),
        ));
        $this->add(array(
            'type' => 'select',
            'name' => 'year_end',
            'options' => array(
                'label' => '&nbsp;',
                'options' => ['' => 'pana la '] + $years,
                //'empty_option' => '--- Selecteaza Parinte ---',
//                'disable_inarray_validator' => true
            ),
            'attributes' => array(
//                'noLabel' => true,
//                'disabled' => 'disabled',
                'id' => 'year_end',
                'group' => array('size' => 'col-lg-2 col-md-2 col-sm-2 col-xs-12', 'type' => ''),
                'required' => true,
                'extraInfo' => 'obligatoriu'
            ),
        ));

        $this->add(array(
            'type' => 'select',
            'name' => 'stare',
            'options' => array(
                'label' => 'Stare',
                'options' => ['nou' => 'Nou', 'second' => 'Second'],
            ),
            'attributes' => array(
//                'id' => 'year_end',
                'group' => array('size' => 'col-lg-4 col-md-4 col-sm-4 col-xs-12', 'type' => 'end'),
                'required' => true,
                'extraInfo' => 'obligatoriu'
            ),
        ));

//--------------------------------------------------------- DETALII ANUNT--------------


        $this->add(array(
            'name' => 'x2',
            'type' => 'hidden',
            'attributes' => array(
                'textAbove' => 'Detalii Anunt',
                'custom_form_spacer' => true,
            )
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'part_name',
            'options' => array(
                'label' => 'Piesa',
//                'options' => [''=>''],
            ),
            'attributes' => array(
                'id' => 'select2CarPartsSub',
//                'group' => array('size' => 'col-sm-8 col-md-4', 'sizeLabel' => 'col-sm-4 col-md-2', 'type' => 'end'),
                'group' => array('size' => 'col-lg-6 col-md-6 col-sm-6 col-xs-12', 'type' => 'start'),
                'extraInfo' => 'obligatoriu: numele piesei'

            ),
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'code_oem',
            'options' => array(
                'label' => 'Cod Piesa',
//                'disable_inarray_validator' => true
            ),
            'attributes' => array(
                'id' => 'code_oem',
                'required' => false,
                'extraInfo' => 'optional: cod unic de origine, OEM',
                'group' => array('size' => 'col-lg-6 col-md-6 col-sm-6 col-xs-12', 'type' => 'end'),
            ),
        ));


        $this->add(array(
            'name' => 'description',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Descriere',
            ),
            'attributes' => array(
                'extraInfo' => 'obligatoriu: detalii anunt, descriere generala a prodului de vanzare, sau orice caracteristica importanta',
                'rows' => 10,
                //'group' => array('size'=> 'col-sm-4','type'=>'end'),
//				'id' => 'last_name',
            ),
        ));

        /*$this->add(array(
            'name' => 'phone',
            'options' => array(
                'label' => 'Stare produs',
            ),
            'attributes' => array(
                'group' => array('size'=> 'col-sm-8 col-md-2', 'sizeLabel'=>'col-sm-4 col-md-2', 'type'=>'start'),
                'type' => 'text',
            ),
        ));*/
        $this->add(array(
            'name' => 'price',
            'options' => array(
                'label' => 'Pret',
            ),
            'attributes' => array(
                'group' => array(
                    'size' => 'col-lg-3 col-md-3 col-sm-4 col-xs-8',
                    'sizeLabel' => 'col-lg-2 col-md-2 col-sm-2 col-xs-12',
                    'type' => 'start'
                ),
                'type' => 'text',
                'extraInfo' => 'obligatoriu: un pret real va ajuta in vanzarea piesei'
            ),
        ));
        $this->add(array(
            'type' => 'select',
            'name' => 'currency',
            'options' => array(
                'label' => '&nbsp;',
                'options' => ['RON' => 'RON', 'EUR' => 'EUR'],
                //'empty_option' => '--- Selecteaza Parinte ---',
//                'disable_inarray_validator' => true
            ),
            'attributes' => array(
//                'noLabel' => true,
//                'disabled' => 'disabled',
//                'id' => 'year_end',
                'group' => array(
                    'size' => 'col-lg-2 col-md-2 col-sm-2 col-xs-4',
                    'sizeLabel' => 'col-lg-2 col-md-2 col-sm-2 col-xs-12',
                    'type' => 'end'
                ),
                'required' => true,
//                'extraInfo' => 'Sau selecteaza din cei adaugati deja in sistem'
            ),
        ));

//--------------------------------------------------------- POZE ANUNT--------------

        $this->add(array(
            'type' => 'hidden',
            'name' => 'x4',
            'attributes' => array(
                'textAbove' => 'Poze',
                'custom_form_spacer' => true,
            )
        ));

        $this->add(array(
            'name' => 'photosMultiUpload',
            'type' => 'text',
            'options' => [
                'label' => '',
            ],
            'attributes' => array(
                'maxFileSize' => '2MB',
                'maxNumberOfFiles' => 5,
                'acceptedFileType' => 'jpeg, png, gif'
//                'noLabel' => true,
                //'textAbove' => 'Detalii Anunt',
            )
        ));

        if ($role == "contentmanager" || $role == "admin") {

            $this->add(array(
                'name' => 'x3',
                'type' => 'hidden',
                'attributes' => array(
                    'textAbove' => 'Detalii Vanzator',
                    'custom_form_spacer' => true,
                )
            ));

            $this->add(array(
                'type' => 'text',
                'name' => 'adv_name',
                'options' => array(
                    'label' => 'Nume Vanzator',
//                'options' => [''=>''],
                ),
                'attributes' => array(
//                    'id' => 'select2CarPartsSub',
//                'group' => array('size' => 'col-sm-8 col-md-4', 'sizeLabel' => 'col-sm-4 col-md-2', 'type' => 'end'),
                    'group' => array('size' => 'col-lg-5 col-md-5 col-sm-4 col-xs-12', 'type' => 'start'),
                    'extraInfo' => 'obligatoriu'
                ),
            ));

            $this->add(array(
                'type' => 'text',
                'name' => 'adv_email',
                'options' => array(
                    'label' => 'Email',
//                'options' => [''=>''],
                ),
                'attributes' => array(
//                    'id' => 'select2CarPartsSub',
//                'group' => array('size' => 'col-sm-8 col-md-4', 'sizeLabel' => 'col-sm-4 col-md-2', 'type' => 'end'),
                    'group' => array('size' => 'col-lg-4 col-md-4 col-sm-4 col-xs-12', 'type' => ''),
                    'extraInfo' => 'optional'
                ),
            ));

            $this->add(array(
                'type' => 'text',
                'name' => 'adv_tel',
                'options' => array(
                    'label' => 'Telefon',
//                'options' => [''=>''],
                ),
                'attributes' => array(
//                    'id' => 'select2CarPartsSub',
//                'group' => array('size' => 'col-sm-8 col-md-4', 'sizeLabel' => 'col-sm-4 col-md-2', 'type' => 'end'),
                    'group' => array('size' => 'col-lg-3 col-md-3 col-sm-4 col-xs-12', 'type' => 'end'),
                    'extraInfo' => 'obligatoriu'
                ),
            ));

            $this->add(array(
                'name' => 'adv_address',
                'type' => 'text',
                'options' => array(
                    'label' => 'Adressa',
                ),
                'attributes' => array(
                    'placeholder' => 'Adresa: strada, nr.',
                    'group' => array('size' => 'col-lg-5 col-md-5 col-sm-4 col-xs-12', 'type' => 'start'),
                    'extraInfo' => 'optional'
                ),
            ));
            $this->add(array(
                'name' => 'adv_city',
                'type' => 'text',
                'options' => array(
                    'label' => 'Oras',
                ),
                'attributes' => array(
//                'noLabel' => true,
                    'group' => array('sizeLabel' => 'col-sm-1', 'size' => 'col-lg-4 col-md-4 col-sm-4 col-xs-12', 'type' => ''),
                    'extraInfo' => 'obligatoriu'
                ),
            ));
            $this->add(array(
                'type' => 'select',
                'name' => 'adv_state',
                'options' => array(
                    'label' => 'Judet',
                    'options' => $states,
                ),
                'attributes' => array(
//                'id' => 'year_end',
                    'group' => array('sizeLabel' => 'col-sm-1', 'size' => 'col-lg-3 col-md-3 col-sm-4 col-xs-12', 'type' => 'end'),
                    'required' => true,
//                'noLabel' => true,
                    'extraInfo' => 'obligaroriu'
                ),
            ));




        }

        /*$this->add(array(
            'name' => 'custom_form_spacer',
            'type' => 'hidden',
            'attributes' => array(
                'name' => 'sdad',
                'id' => 'x2',
                'textAbove' => 'zzz',
            )
        ));*/

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => $resourceObj->getId() === null ? 'Adauga anuntul' : 'Actualizeaza anuntul',
                'id' => 'submitbutton',
                'cancelLink' => $this->_cancel_route
            ),
        ));
    }

}
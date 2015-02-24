<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Forms\AdForm;
use Application\Forms\Filters\AdFilter;
use Application\libs\General;
use Application\Models\Ads\Ad;
use Application\Models\Ads\AdDM;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Helper\Json;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use ZfcBaseTest\Mapper\AbstractDbMapperTest;

class AdController extends MyAbstractController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function createAction()
    {
        $cars = $this->cars;

        $this->layout()->js_call .= ' generalObj.cars = '.json_encode($cars).'; ';
        $this->layout()->js_call .= ' generalObj.ad.create("'.$this->url()->fromRoute("home/ad/upload").'"); ';

        $make = $this->cars['make'];
        $partsMain = $this->cars['partsMain'];

        General::addToSession('adTmpId','tmp'.rand(10000,99999));

        $carburant = General::getConfigs($this, 'consts|carburant');
        $cilindree = General::getConfigs($this, 'consts|cilindree');

        $resourceObj = new Ad();
        $form = new AdForm();
        $form->setCancelRoute('back');
        $form->create($resourceObj, $make, $carburant, $cilindree, $partsMain);


        $request = $this->getRequest();

        $resourceObj->setUserId($this->myUser->getId());

        if ($request->isPost()) {
            $filter = new AdFilter();
            $form->setInputFilter($filter->getInputFilter());

            $form->setData($request->getPost());
            if ($form->isValid()) {

                $carburantValue = isset($carburant[$form->get('car_carburant')->getValue()]) ?
                    $carburant[$form->get('car_carburant')->getValue()] :
                    ''
                ;
                $cilindreeValue = isset($cilindree[$form->get('car_cilindree')->getValue()]) ?
                    $cilindree[$form->get('car_cilindree')->getValue()] :
                    ''
                ;
                $resourceObj
                    ->setCarModel($form->get('car_model')->getValue())
                    ->setCarCarburant($carburantValue)
                    ->setCarCilindree($cilindreeValue)
                    ->setUserId($this->myUser->getId())
                    ->setStatus('pending')
                ;
                $adDM = new AdDM($this->adapter);
                $adDM->createRow($resourceObj);

                $this->redirect()->toRoute('home');
            } else {
                $this->layout()->js_call .=
                    ' generalObj.ad.changeClass("'.$form->get('car_class')->getValue().'");'.
                    ' generalObj.ad.changeModel("'.$form->get('car_model')->getValue().'"); ';
            }
        }

        return [
            'form' => $form
        ];
    }


    public function uploadAction()
    {
        $option = $this->getEvent()->getRouteMatch()->getParam('option', '');

        if ($option == '' && $this->getRequest()->isPost()) {
            // we're using user_id and email here as a way to
            // verify the upload and store the file in a specific directory,
            // you can strip that out for your purposes.
            if (!is_dir(PUBLIC_PATH . $this->myUser->getId() . '/')) {
                mkdir(PUBLIC_PATH . $this->myUser->getId() . '/');
                chmod(PUBLIC_PATH . $this->myUser->getId() . '/', '0777');
            }
            $this->upload($this->myUser->getId(),
                $this->myUser->getEmail(),
                PUBLIC_PATH . $this->myUser->getId() . '/' . General::getFromSession('adTmpId') . '/'
            );
        }

        if ($this->getRequest()->isGet()) {
            //$this->upload( $this->session->user['id'], $this->session->user['email'] );
        }
        if ($this->getRequest()->isDelete() || $_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $this->delete( $this->myUser->getId(), $this->myUser->getEmail() );
        }
        exit;
    }
}

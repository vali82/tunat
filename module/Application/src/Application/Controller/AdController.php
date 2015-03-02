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

        $adTmpId = General::getFromSession('adTmpId');

        if ($adTmpId === null) {
            $adTmpId = 'tmp'.rand(10000,99999);
            General::addToSession('adTmpId', $adTmpId);
        }

        $file_path = PUBLIC_PATH . $this->myUser->getId() . '/ads/'. $adTmpId . '/' ;

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


                $images = [];

                if (is_dir($file_path)) {
                    foreach (glob($file_path . "*") as $filefound) {
                        $x = explode('/', $filefound);
                        $filename = $x[count($x)-1];
                        if (strpos($filename, '_') === false) {
                            $images[] = $filename;
                        }
                    }
                }

                $resourceObj
                    ->setCarModel($form->get('car_model')->getValue())
                    ->setCarCarburant($carburantValue)
                    ->setCarCilindree($cilindreeValue)
                    ->setUserId($this->myUser->getId())
                    ->setStatus('pending')
                    ->setImages(serialize($images))
                ;
                $adDM = new AdDM($this->adapter);
                $adId = $adDM->createRow($resourceObj);

                if (is_dir($file_path)) {
                    rename($file_path, PUBLIC_PATH . $this->myUser->getId() . '/ads/' . $adId . '/');
                }


                General::unsetSession('adTmpId');

                $this->redirect()->toRoute('home');
            } else {
                $this->layout()->js_call .=
                    ' generalObj.ad.changeClass("'.$form->get('car_class')->getValue().'");'.
                    ' generalObj.ad.changeModel("'.$form->get('car_model')->getValue().'"); ';
            }
        }

        if (is_dir($file_path)) {
//            var_dump('asdads');
//            $this->layout()->js_call .=' generalObj.ad.callUpload("'.$this->url()->fromRoute('home/ad/upload').'"); ';
        }

        return [
            'form' => $form
        ];
    }


    public function uploadAction()
    {
        $option = $this->getEvent()->getRouteMatch()->getParam('option', '');

        if ($option == '' && $this->getRequest()->isPost()) {
            return $this->uploadAdImages(
                $this->myUser->getId(),
                $this->myUser->getEmail(),
                ['ads', General::getFromSession('adTmpId')],
                ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'],
                2*1024*1024
            );
        }
        if ($this->getRequest()->isGet()) {
            return  $this->uploadAdGetUploaded(
                $this->myUser->getId(),
                $this->myUser->getEmail(),
                ['ads', General::getFromSession('adTmpId')]
            );
        }
        if ($this->getRequest()->isDelete() || $_SERVER['REQUEST_METHOD'] == 'DELETE') {
            return $this->deleteAdImages($this->myUser->getId(), $this->myUser->getEmail());
        }
        exit;
    }
}

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
use Application\Models\Ads\AdCollection;
use Application\Models\Ads\AdDM;
use Application\Models\Cars\CarsCollection;
use Zend\View\Model\ViewModel;

class AdController extends MyAbstractController
{
    public function indexAction()
    {
        return new ViewModel();
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

    public function pieseAction()
    {

        $cars = $this->cars;
        $carCollection = new CarsCollection($this);

        $makeParam = $this->getEvent()->getRouteMatch()->getParam('car_make', '');
        $modelParam = $this->getEvent()->getRouteMatch()->getParam('car_model', '');
        $classParam = $this->getEvent()->getRouteMatch()->getParam('car_class', null);
        $partMain = $this->getEvent()->getRouteMatch()->getParam('parts_main', '');
        $adParam = $this->getEvent()->getRouteMatch()->getParam('ad_id', '');

        // detect Car Make ID
        $carMakeId = null;
        $x = explode('-', $makeParam);
        if ($makeParam != '' && is_array($x) && count($x) > 0) {
            $carMakeId = $x[count($x)-1];
            if (!isset($cars['model'][$carMakeId])) {
                $carMakeId = null;
            }
        }
        ////

        if ($carMakeId === null) {
            $this->redirect()->toRoute('home');
        }

        // detect Car Class
        $models = null;
        $class = null;
        if ($classParam !== null) {
            $models = [];
            foreach ($cars['model'][$carMakeId] as $modelId => $model) {
                if ($carCollection->getUrlize($model['categ']) == $classParam) {
                    $class = $model['categ'];
                    $models[$modelId] = $model;
                }
            }
        }
        ////

        // detect Car Model ID
        $carModelId = null;
        $x = explode('-', $modelParam);
        if ($modelParam != '' && is_array($x) && count($x) > 0) {
            $carModelId = $x[count($x)-1];
            if (!isset($cars['model'][$carMakeId][$carModelId])) {
                $carModelId = null;
            }
        }
        ////

        // detect Car Part ID
        $partMainId = null;
        $x = explode('-', $partMain);
        if ($partMain != '' && is_array($x) && count($x) > 0) {
            $partMainId = $x[count($x)-1];
            if (!isset($cars['partsMain'][$partMainId])) {
                $partMainId = null;
            }
        }
        ////

        // get All ADs with these IDs
        $adList = null;
        if ($carModelId !== null && $partMainId !== null) {
            $ad = new AdCollection($this);

            $adList = $ad->adListHTML([
                'car_model' => $carModelId,
                'part_categ' => $partMainId
            ]);
        }
        ////

        // detect Ad ID
        $adId = null;
        $x = explode('-', $adParam);
        if ($adParam != '' && is_array($x) && count($x) > 0) {
            $adId = $x[count($x)-1];

            General::echop($adId);
            $adView = $ad->viewHTML($adId);

        }
        ////

        return [
            'carMakeId' => $carMakeId,
            'class' => $class,
            'models' => $models,
            'carModelId' => $carModelId,
            'partMainId' => $partMainId,
            'breadcrump' => $carCollection->breadcrump($carMakeId, $class, $carModelId, $partMainId),
            'carCollection' => $carCollection,
            'adList' => $adList,
            'adView' => $adView

        ];
    }

    public function viewAdAction()
    {

    }

}

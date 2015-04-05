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
use Application\Models\Autoparks\ParksDM;
use Application\Models\Cars\CarsCollection;
use Zend\View\Model\JsonModel;
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
                $this->myPark->getId(),
                $this->myUser->getEmail(),
                ['ads', General::getFromSession('adTmpId')],
                ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'],
                2*1024*1024
            );
        }
        if ($this->getRequest()->isGet()) {
            return  $this->uploadAdGetUploaded(
                $this->myPark->getId(),
                $this->myUser->getEmail(),
                ['ads', General::getFromSession('adTmpId')]
            );
        }
        if ($this->getRequest()->isDelete() || $_SERVER['REQUEST_METHOD'] == 'DELETE') {
            return $this->deleteAdImages($this->myPark->getId(), $this->myUser->getEmail());
        }
        exit;
    }

    public function createAction()
    {
        $cars = $this->cars;
        $id = $this->getEvent()->getRouteMatch()->getParam('id', null);

        $this->layout()->js_call .= ' generalObj.cars = '.json_encode($cars).'; ';
        $this->layout()->js_call .= ' generalObj.ad.create("'.$this->url()->fromRoute("home/ad/upload").'"); ';

        if ($id !== null) {
            // EDIT
            $adDM = new AdDM($this->adapter);
            $resourceObj = $adDM->fetchOne([
                'id' => $id,
                'park_id' => $this->myPark->getId()
            ]);
            if ($resourceObj === null) {
                $this->flashMessenger()->addErrorMessage('A aparut o eroare! Anunt invalid!');
                $this->redirect()->toRoute('home/ad/myAds', ['status'=>'active']);
            }
            $adTmpId = $id;
            General::addToSession('adTmpId', $adTmpId);

            $this->layout()->js_call .=
                ' generalObj.ad.changeClass("'.$resourceObj->getCarMake().'");'
                .
                ' generalObj.ad.changeModel("'.$resourceObj->getCarModel().'"); '
            ;

        } else {
            // ADD
            $adTmpId = General::getFromSession('adTmpId');

            if ($adTmpId === null) {
                $adTmpId = 'tmp'.rand(10000, 99999);
                General::addToSession('adTmpId', $adTmpId);
            }
            $resourceObj = new Ad();
        }

        $file_path = PUBLIC_IMG_PATH . $this->myPark->getId() . '/ads/'. $adTmpId . '/' ;

        $form = new AdForm();
        $form->setCancelRoute('back');
        $form->create($resourceObj, $this->cars['categories'], null, null, null);

        $form->bind($resourceObj);

        $request = $this->getRequest();

        $resourceObj->setParkId($this->myPark->getId());

        if ($request->isPost()) {
            $filter = new AdFilter();
            $form->setInputFilter($filter->getInputFilter());

            $form->setData($request->getPost());
            if ($form->isValid()) {
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

                // detect Car Class
                $carMakelId = $form->get('car_make')->getValue();
                ////

                $resourceObj
                    ->setCarMake($carMakelId)
                    ->setStatus('ok')
                    ->setImages(serialize($images))
                    ->setViews(0)
                    ->setContactDisplayed(0)
                ;
                $adDM = new AdDM($this->adapter);

                if ($id === null) {
                    $adId = $adDM->createRow($resourceObj);


                    if ($adId && is_dir($file_path)) {
                        rename($file_path, PUBLIC_IMG_PATH . $this->myPark->getId() . '/ads/' . $adId . '/');
                    }
                    $this->flashMessenger()->addSuccessMessage('Anuntul a fost adaugat cu success!');

                } else {
                    $adId = $adDM->updateRow($resourceObj);
                    $this->flashMessenger()->addSuccessMessage('Anuntul a fost modificat cu success!');

                }
                General::unsetSession('adTmpId');

                $this->redirect()->toRoute('home/ad/myAds');
            } else {
                $this->layout()->js_call .=
                    ' generalObj.ad.changeClass("'.$form->get('car_make')->getValue().'");'
                    .
                    ' generalObj.ad.changeModel("'.$form->get('car_model')->getValue().'"); '
                ;
            }
        }

        return [
            'form' => $form,
            'id' => $id,
            'title' => $id !== null ? 'Modifica Anunt' : 'Adauga Anunt'
        ];
    }

    public function myAdsAction()
    {
        $statusParam = $this->getEvent()->getRouteMatch()->getParam('status', 'active');
        switch ($statusParam) {
            case "active":
                $status = 'ok';
                $title = 'Anunturi Active';
                break;
            case "expired":
                $title = 'Anunturi Expirate';
                $status = 'expired';
                break;
            default:
                $status = 'ok';
        }
        $cars = $this->cars;
        $carCollection = new CarsCollection($this);
        $ad = new AdCollection($this);

        $token = md5(time().'asdfqwer'.rand(1000, 9999));
        General::addToSession('token', $token);
        $content = $ad->adListHTML([
            'place' => 'myAds',
            'status' => $status,
            'token' => $token
        ]);

        $adList = $content['list'];
        $ads = $content['ads'];
        return [
            'adList' => $adList,
            'ads' => $ads,
            'carCollection' => $carCollection,
            'statusParam' => $statusParam,
            'title' => $title
        ];
    }

    public function pieseAction()
    {
        $cars = $this->cars;
        $carCollection = new CarsCollection($this);

        $categoriesParam = $this->getEvent()->getRouteMatch()->getParam('categories', '');
        $modelParam = $this->getEvent()->getRouteMatch()->getParam('car_model', '');
        $classParam = $this->getEvent()->getRouteMatch()->getParam('car_class', null);
        $partMain = $this->getEvent()->getRouteMatch()->getParam('parts_main', '');
        $adParam = $this->getEvent()->getRouteMatch()->getParam('ad_id', '');


        // detect Car categories ID
        $carcategoriesId = null;
        if ($categoriesParam !== '') {
            foreach ($cars['categories'] as $categId => $model) {
                if (strtolower($carCollection->getUrlize($model)) == $categoriesParam) {
                    $carcategoriesId = $categId;
                }
            }
        }
        ////


        if ($carcategoriesId == null || !isset($cars['model'][$carcategoriesId])) {
            return $this->redirect()->toRoute('home');
        }



        // detect Car Class
        $models = null;
        $class = null;
        $carModelId = null;
        if ($classParam !== null) {
            $models = [];
            foreach ($cars['model'][$carcategoriesId] as $modelId => $model) {
                if ($carCollection->getUrlize($model['categ']) == $classParam) {
                    $class = $model['categ'];
                    $models[$modelId] = $model;
                    $carModelId = $modelId;
                }
            }
        }
        ////

//        var_dump($cars['model'][$carcategoriesId]); die();


        // detect Ad ID
        $ad = new AdCollection($this);
        $adId = null;
        $adView = null;
        $x = explode('-', $adParam);
        if ($adParam != '' && is_array($x) && count($x) > 0) {
            $adId = (int)$x[count($x)-1];
            $adView = $ad->viewHTML($adId);
            if ($adView === null) {
                //$this->flashMessenger()->addInfoMessage('Anuntul #'.$adId.' a expirat');
                return $this->redirect()->toRoute('home');
            }
        }
        ////



        // get All ADs with these IDs
        $adList = null;
        $ads = null;
        if ($adView === null) {
            $content = $ad->adListHTML([
                'place' => 'onSearch',
                'carModelId' => $carModelId,
                'partMainId' => 0
            ]);

            $adList = $content['list'];
            $ads = $content['ads'];
        }
        ////



        $urlGetContact = $this->url()->fromRoute('home/ad/getContact', ['id'=>($adId !== null ? $adId : 0)]);
        $this->layout()->js_call .= ' generalObj.ad.search.init("'.$urlGetContact.'"); ';



        return [
            'carcategoriesId' => $carcategoriesId,
            'class' => $class,
            'models' => $models,
            'carModelId' => $carModelId,
//            'partMainId' => $partMainId,
            'breadcrump' => $carCollection->breadcrump($carcategoriesId, $class, null, null),
            'carCollection' => $carCollection,
            'adList' => $adList,
            'ads' => $ads,
            'adView' => $adView
        ];
    }

    public function getContactAction()
    {
        $id = $this->getEvent()->getRouteMatch()->getParam('id', '');
        $adDM = new AdDM($this->getAdapter());
        /** @var $adObj \Application\Models\Ads\Ad*/
        $adObj = $adDM->fetchOne([
            'id' => $id,
            'status' => 'ok'
        ]);
        $parkObj = null;
        if ($adObj !== null) {
            $parkDM = new ParksDM($this->getAdapter());
            /** @var $parkObj \Application\Models\Autoparks\Park */
            $parkObj = $parkDM->fetchOne($adObj->getParkId());

            // count number of view contacts
            if ($this->myPark === null ||
                $this->myPark->getId() !== $adObj->getParkId()) {
                $adObj->setContactDisplayed($adObj->getContactDisplayed() + 1);
                $adDM->updateRow($adObj);
            }
            ////
        }


        return new JsonModel([
            'error' => $parkObj !== null ? 0 : 1,
            'result' =>  $parkObj !== null ? [
                'name' => $parkObj->getName(),
                'tel1' => $parkObj->getTel1(),
                'email' => $parkObj->getEmail(),
                'url' => $parkObj->getUrl(),
                'location' => $parkObj->getLocation()
            ] : null,
            'message' => $parkObj !== null ? '' : 'Datele de contact nu au fost gasite',
        ]);
    }

    public function changeStatusAction()
    {
        $token = $this->getEvent()->getRouteMatch()->getParam('token', '');
        $id = $this->getEvent()->getRouteMatch()->getParam('id', '');
        $mode = $this->getEvent()->getRouteMatch()->getParam('mode', '');
        $messageError = '';
        $messageSuccess = '';
        $error = 0;
        $statusRedirect = 'active';

        if ($token == General::getFromSession('token')) {
            $adDM = new AdDM($this->adapter);
            /** @var $adObj \Application\Models\Ads\Ad*/
            $adObj = $adDM->fetchOne([
                'id' => $id,
                'park_id' => $this->myPark->getId()
            ]);
            if ($adObj !== null) {
                if ($mode == 'delete') {
                    $adDM->deleteOne($adObj);
                    $file_path = PUBLIC_IMG_PATH . $this->myPark->getId() . '/ads/' . $id;
                    foreach (glob($file_path . "/*") as $filefound) {
                        @unlink($filefound);
                    }
                    rmdir($file_path);

                    $messageSuccess = 'Anuntul a fost sters cu success!';

                } elseif ($mode == 'activate') {
                    $adObj
                        ->setDateadd(General::DateTime())
                        ->setStatus('ok')
                    ;
                    $adDM->updateRow($adObj);
                    $messageSuccess = 'Anuntul a fost activat cu success!';
                    $statusRedirect = 'expired';
                }
            } else {
                $error = 1;
                $messageError = 'Anunt invalid!';
            }


        } else {
            $messageError = 'Token invalid!';
            $error = 1;
        }

        if ($error) {
            $this->flashMessenger()->addErrorMessage($messageError);
        } else {
            $this->flashMessenger()->addSuccessMessage($messageSuccess);
        }

        $this->redirect()->toRoute('home/ad/myAds', ['status' => $statusRedirect]);
    }
}

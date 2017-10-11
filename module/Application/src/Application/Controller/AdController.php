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
use Application\Mail\MailGeneral;
use Application\Models\Ads\Ad;
use Application\Models\Ads\AdCollection;
use Application\Models\Ads\AdDM;
use Application\Models\Advertiser\Advertiser;
use Application\Models\Advertiser\AdvertiserDM;
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
        $advId = (int)$this->getEvent()->getRouteMatch()->getParam('adv_id', 0);

        if ($option == '' && $this->getRequest()->isPost()) {
            return $this->uploadImages(
                $advId > 0 ? $advId : $this->myAdvertiserObj->getId(),
                ['ads', General::getFromSession('adTmpId')],
                ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'],
                2*1024*1024
            );
        }
        if ($this->getRequest()->isGet()) {
            return  $this->uploadGetUploaded(
                $advId > 0 ? $advId : $this->myAdvertiserObj->getId(),
                ['ads', General::getFromSession('adTmpId')]
            );
        }
        if ($this->getRequest()->isDelete() || $_SERVER['REQUEST_METHOD'] == 'DELETE') {
            return $this->uploadDeleteImages();
        }
        exit;
    }

    public function createAction()
    {
        $this->layout()->setVariable('myAccountMenu', [
            'active' => 'addad'
        ]);

        $states = General::getFromSession('states');
        unset($states[0]); // delete the "oricare" value

        $cars = $this->cars;
        $id = $this->getEvent()->getRouteMatch()->getParam('id', null);

        $this->layout()->js_call .= ' generalObj.cars = '.json_encode($cars).'; ';


        $request = $this->getRequest();
        $adCollection = new AdCollection($this);

        if ($id !== null) {
            // EDIT
            $this->layout()->setVariable('myAccountMenu', [
                'active' => 'editad'
            ]);
            $adDM = new AdDM($this->adapter);
            if ($this->role == 'contentmanager' || $this->role == 'admin') {
                $resourceObj = $adDM->fetchOne([
                    'id' => $id,
//                    'advertiser_id' => $this->myAdvertiserObj->getId()
                ]);
            } else {
                $resourceObj = $adDM->fetchOne([
                    'id' => $id,
                    'advertiser_id' => $this->myAdvertiserObj->getId()
                ]);
            }

            if ($resourceObj === null) {
                $this->flashMessenger()->addErrorMessage('A aparut o eroare! Anunt invalid!');
                return $this->redirect()->toRoute('home/ad/myAds', ['status'=>'active']);
            }
            $adTmpId = $id;
            General::addToSession('adTmpId', $adTmpId);

            $file_path = PUBLIC_IMG_PATH . $resourceObj->getAdvertiserId();

            $this->layout()->js_call .= ' generalObj.ad.create("'.$this->url()->fromRoute("home/ad/upload", ['adv_id'=>$resourceObj->getAdvertiserId()]).'"); ';
            $this->layout()->js_call .=
                ' generalObj.ad.changeClass("'.$resourceObj->getCarMake().'");'
                .
                ' generalObj.ad.changeModel("'.$resourceObj->getCarModel().'"); '
            ;

        } else {
            $file_path = PUBLIC_IMG_PATH . $this->myAdvertiserObj->getId();
            $this->layout()->js_call .= ' generalObj.ad.create("'.$this->url()->fromRoute("home/ad/upload", ['adv_id'=>0]).'"); ';
            // ADD
            if ($request->isPost()) {
                $adTmpId = General::getFromSession('adTmpId');
            } else {
                $adTmpId = 'tmp'.rand(10000, 99999);
                General::addToSession('adTmpId', $adTmpId);
            }

            $resourceObj = new Ad();
        }

        $file_path .= '/ads/'. $adTmpId . '/';

        $form = new AdForm();
        $form->setCancelRoute('back');
        $years = $adCollection->getYears();
        $form->create($resourceObj, $this->cars['categories'], $years, $this->role, $states);

        $form->bind($resourceObj);

        if ($request->isPost()) {
            $filter = new AdFilter($this->role);
            $form->setInputFilter($filter->getInputFilter());

            $form->setData($request->getPost());
            if ($form->isValid()) {
                $images = [];



                if (is_dir($file_path)) {
                    foreach (glob($file_path . "*") as $filefound) {
                        $x = explode('/', $filefound);
                        $filename = $x[count($x)-1];
                        if (strpos($filename, '_') === false) {
                            $images[date('YmdHis', filemtime($filefound)).rand(1000,9999)] = $filename;
                        }
                    }
                    ksort($images);
                }

                // detect Car Class
                $carMakelId = $form->get('car_make')->getValue();
                ////

                $expDate = General::DateTime(null, 'object');
                $expDate->add(new \DateInterval('P30D'));
                $resourceObj
                    ->setCarMake($carMakelId)
                    ->setStatus('ok')
                    ->setImages(serialize(array_values($images)))
                    ->setUpdatedAt(General::DateTime())
                ;
                if ($id === null) {
                    $resourceObj
                        ->setViews(0)
                        ->setContactDisplayed(0)
                    ;
                }
                $resourceObj->setPrice(str_replace(',', '.', $resourceObj->getPrice()));
                $adDM = new AdDM($this->adapter);

                if ($id === null) {
                    // create adv if is content manager
                    if ($this->role == 'contentmanager' || $this->role == 'admin') {
                        $advDM = new AdvertiserDM($this->adapter);
                        $advObj = new Advertiser();
                        $advObj
                            ->setAccountType('unregistered')
                            ->setAddress($filter->getInputFilter()->getValue('adv_address'))
                            ->setCity($filter->getInputFilter()->getValue('adv_city'))
                            ->setDescription('')
                            ->setEmail($filter->getInputFilter()->getValue('adv_email'))
                            ->setLogo('')
                            ->setName($filter->getInputFilter()->getValue('adv_name'))
                            ->setState($filter->getInputFilter()->getValue('adv_state'))
                            ->setTel1($filter->getInputFilter()->getValue('adv_tel'))
                            ->setTel2('')
                            ->setTel3('')
                            ->setUrl('')
                        ;
                        $advId = $advDM->createRow($advObj);

                    } else {
                        $advId = $this->myAdvertiserObj->getId();
                    }


                    $resourceObj->setExpirationDate(General::DateTime($expDate));
                    $resourceObj->setAdvertiserId($advId);
                    $adId = $adDM->createRow($resourceObj);



                    if ($adId && is_dir($file_path)) {
                        $pathAdv = PUBLIC_IMG_PATH . $advId . '/';
                        if (!is_dir($pathAdv)) {
                            mkdir($pathAdv);
                            chmod($pathAdv, 0755);
                            mkdir($pathAdv . '/ads/');
                            chmod($pathAdv . '/ads/', 0755);
                        }
                        rename($file_path, PUBLIC_IMG_PATH . $advId . '/ads/' . $adId . '/');
                    }
                    $this->flashMessenger()->addSuccessMessage('Anuntul a fost adaugat cu success!');

                } else {
                    // update adv if is content manager
                    if (($this->role == 'contentmanager' || $this->role == 'admin') &&
                        $resourceObj->getAdvertiserId() !== $this->myAdvertiserObj->getId()
                    ) {
                        $advDM = new AdvertiserDM($this->adapter);
                        $advObj = $advDM->fetchOne($resourceObj->getAdvertiserId());
                        if ($advObj !== null) {
                            $advObj
                                ->setAccountType('unregistered')
                                ->setAddress($filter->getInputFilter()->getValue('adv_address'))
                                ->setCity($filter->getInputFilter()->getValue('adv_city'))
                                ->setEmail($filter->getInputFilter()->getValue('adv_email'))
                                ->setName($filter->getInputFilter()->getValue('adv_name'))
                                ->setState($filter->getInputFilter()->getValue('adv_state'))
                                ->setTel1($filter->getInputFilter()->getValue('adv_tel'))
                            ;
                        }
                        $advDM->updateRow($advObj);
                    }

                    $adDM->updateRow($resourceObj);
                    $this->flashMessenger()->addSuccessMessage('Anuntul a fost modificat cu success!');

                }
                General::unsetSession('adTmpId');

                return $this->redirect()->toRoute('home/ad/myAds');
            } else {
                $this->layout()->js_call .=
                    ' generalObj.ad.changeClass("'.$form->get('car_make')->getValue().'");'
                    .
                    ' generalObj.ad.changeModel("'.$form->get('car_model')->getValue().'"); '
                ;
            }
        } else {
            if ($id !== null && ($this->role == 'contentmanager' || $this->role == 'admin') &&
                $resourceObj->getAdvertiserId() !== $this->myAdvertiserObj->getId()
            ) {
                // anuntul nu este personal ... atunci este pentru un alt advertiser

                $advDM = new AdvertiserDM($this->adapter);
                $advObj = $advDM->fetchOne($resourceObj->getAdvertiserId());
                $form->populateValues([
                    'adv_name' => $advObj->getName(),
                    'adv_email' => $advObj->getEmail(),
                    'adv_tel' => $advObj->getTel1(),
                    'adv_address' => $advObj->getAddress(),
                    'adv_city' => $advObj->getCity(),
                    'adv_state' => $advObj->getState()
                ]);
            }
            if ($id !== null) {
                $form->populateValues([
                    'price' => str_replace('.', ',', $resourceObj->getPrice())
                ]);
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
        $this->layout()->setVariable('myAccountMenu', [
            'active' => 'myads'
        ]);
        $statusParam = $this->getEvent()->getRouteMatch()->getParam('status', 'active');
        switch ($statusParam) {
            case "active":
                $status = 'ok';
                $title = 'Anunturi Active';
                break;
            case "inactive":
                $title = 'Anunturi Inactive';
                $status = 'inactive';
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
            'token' => $token,
            'role' => $this->role
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
            if ($this->role == 'admin') {
                $adObj = $adDM->fetchOne([
                    'id' => $id,
                ]);
            } else {
                $adObj = $adDM->fetchOne([
                    'id' => $id,
                    'advertiser_id' => $this->myAdvertiserObj->getId()
                ]);
            }


            if ($adObj !== null) {
                if ($mode == 'delete') {
                    $adDM->deleteOne($adObj);
                    $file_path = PUBLIC_IMG_PATH . $adObj->getAdvertiserId() . '/ads/' . $id;
                    foreach (glob($file_path . "/*") as $filefound) {
                        @unlink($filefound);
                    }
                    rmdir($file_path);

                    $messageSuccess = 'Anuntul a fost sters cu success!';

                } elseif ($mode == 'activate') {
                    $expDate = General::DateTime(null, 'object');
                    $expDate->add(new \DateInterval('P30D'));
                    $adObj
                        ->setExpirationDate(General::DateTime($expDate))
//                        ->setDateadd(General::DateTime())
                        ->setUpdatedAt(General::DateTime())
                        ->setStatus('ok')
                    ;
                    $adDM->updateRow($adObj);
                    $messageSuccess = 'Anuntul a fost activat cu success!';
                    $statusRedirect = 'inactive';

                } elseif ($mode == 'disable') {
//                    $expDate = General::DateTime(null, 'object');
//                    $expDate->add(new \DateInterval('P30D'));
                    $adObj
                        //->setExpirationDate(General::DateTime($expDate))
//                        ->setDateadd(General::DateTime())
//                        ->setUpdatedAt(General::DateTime())
                        ->setStatus('inactive')
                    ;
                    $adDM->updateRow($adObj);
                    $messageSuccess = 'Anuntul a fost dezactivat cu success!';
                    $statusRedirect = 'inactive';
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

        return $this->redirect()->toRoute('home/ad/myAds', ['status' => $statusRedirect]);
    }

    public function pieseAction()
    {
        $token = md5(time().'asdfqwer'.rand(1000, 9999));
        General::addToSession('token', $token);

        $cars = $this->cars;
        $carCollection = new CarsCollection($this);

        $categoriesParam = $this->getEvent()->getRouteMatch()->getParam('categories', '');
//        $modelParam = $this->getEvent()->getRouteMatch()->getParam('car_model', '');
        $classParam = $this->getEvent()->getRouteMatch()->getParam('car_class', null);
//        $partMain = $this->getEvent()->getRouteMatch()->getParam('parts_main', '');
        $adParam = $this->getEvent()->getRouteMatch()->getParam('ad_id', '');
        $searchParam = $this->getEvent()->getRouteMatch()->getParam('search', '');


        $relatedAds = null;

        $searchWords = '';
        $searchYear = '';
        $searchStare = '';
        $searchCounty = '';
        $searchOem = '';
        if (strpos($searchParam, ":") !== false) {
            $search = explode(":", $searchParam);
            $searchWords = $search[0];
            $searchYear = $search[1];
            $searchStare = $search[2];
            $searchCounty = $search[3];
            $searchOem = $search[4];
        } elseif ($searchParam != '') {
            $searchWords = $searchParam;
        }


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



        // detect Ad ID -  VIEW AD
        $adCollection = new AdCollection($this);
        $adId = null;
        $adView = null;
        $x = explode('-', $adParam);
        if ($adParam != '' && strpos($adParam, '-') !== false && is_array($x) && count($x) > 0) {
            $adId = (int)$x[count($x)-1];
            $adView = $adCollection->viewHTML($adId);
            if ($adView === null) {
                //$this->flashMessenger()->addInfoMessage('Anuntul #'.$adId.' a expirat');
                return $this->redirect()->toRoute('home');
            } else {
                // Id anunt este modificat sau nume piesa url diferita => redirect la pagina anuntului propriu-zis
                $adObj = $adView[1];
                if ($carModelId != $adObj->getCarMake() ||
                    $carCollection->getUrlize($adObj->getPartName()).'-'.$adObj->getId() != $adParam
                ) {
                    $route = $carCollection->urlizeAD($adObj, true);
                    return $this->redirect()->toRoute($route[0], $route[1]);
                }
                $adView = $adView[0];
                $relatedAds = $adCollection->adListHTML([
                    'place' => 'related',
                    'carModelId' => $carModelId,
                    'notThisID' => $adId
                ]);
            }
        }
        ////


        // get ADs list
        $adList = null;
        $ads = null;
        if ($adView === null) {
            $content = $adCollection->adListHTML([
                'place' => 'onSearch',
                'carModelId' => $carModelId,
                'partMainId' => 0,
                'search' => General::generateQueryWords($searchWords),
                'searchAdvertiser' => 0,
                'searchYear' => $searchYear,
                'searchCounty' => $searchCounty,
                'searchStare' => $searchStare,
                'searchOem' => $searchOem
            ]);

            $adList = $content['list'];
            $ads = $content['ads'];
        }
        ////

        // ads on category page
        $ads4categoryPage = null;
        if ($carcategoriesId > 0 && $class == null) {
            $ads4categoryPage = $adCollection->adListHTML([
                'place' => 'categoryPage',
                'category' => $carcategoriesId
            ]);
            $ads4categoryPage = $ads4categoryPage['list'];
        }
        ////

        $urlGetContact = $this->url()->fromRoute(
            'home/ad/getContact',
            [
                'id'=>($adId !== null ? $adId : 0),
                'token' => General::getFromSession('token')
            ]
        );
        $this->layout()->js_call .= ' generalObj.ad.search.init("'.$urlGetContact.'"); ';

        $viewVariables = [
            'carcategoriesId' => $carcategoriesId,
            'class' => $class,
            'models' => $models,
            'carModelId' => $carModelId,
//            'partMainId' => $partMainId,
//            'breadcrump' => $carCollection->breadcrump($carcategoriesId, $class, null, null),
            'carCollection' => $carCollection,
            'adList' => $adList,
            'ads' => $ads,
            'ads4categoryPage' => $ads4categoryPage,
            'adView' => $adView,
            'searchValues' => [
                'input' => str_replace("+", " ", $searchWords),
                'year' => $searchYear,
                'county' => $searchCounty,
                'stare' => $searchStare,
                'oem' => $searchOem
            ],
//            'searchValue' => str_replace("+", " ", $search[0]),
//            'searchYearStart' => ($search[1]),
            'years' => $adCollection->getYears(),
            'states' => General::getFromSession('states'),
            'relatedAds' => $relatedAds['list'],
            ''
        ];

        if (!$this->getRequest()->isXmlHttpRequest()) {
            return $viewVariables;
        } else {
            $partial = $this->getServiceLocator()->get('viewhelpermanager')->get('partial');
            $data = [
                'error' => 0,
                'result' => [
                    'html' => $partial('application/ad/piese.phtml', $viewVariables),
                    'js' => ' generalObj.setAjaxCoolEvents(false, false); ' . $this->layout()->js_call .
                        $this->layout()->googleAnalitics
                ]
            ];
            return new JsonModel($data);
        }
    }

    public function getContactAction()
    {
        $id = $this->getEvent()->getRouteMatch()->getParam('id', '');
        $token = $this->getEvent()->getRouteMatch()->getParam('token', '');

        if ($token === General::getFromSession('token')) {
            $adDM = new AdDM($this->getAdapter());
            /** @var $adObj \Application\Models\Ads\Ad */
            $adObj = $adDM->fetchOne([
                'id' => $id,
                'status' => 'ok'
            ]);
            $advertiserObj = null;
            if ($adObj !== null) {
                $advertiserDM = new AdvertiserDM($this->getAdapter());
                /** @var $advertiserObj \Application\Models\Advertiser\Advertiser */
                $advertiserObj = $advertiserDM->fetchOne($adObj->getAdvertiserId());

                // count number of view contacts
                if ($this->myAdvertiserObj === null ||
                    $this->myAdvertiserObj->getId() !== $adObj->getAdvertiserId()
                ) {
                    $adObj->setContactDisplayed($adObj->getContactDisplayed() + 1);
                    $adDM->updateRow($adObj);
                }
                ////
            }
            return new JsonModel([
                'error' => $advertiserObj !== null ? 0 : 1,
                'result' =>  $advertiserObj !== null ? [
                    'name' => $advertiserObj->getName(),
                    'tel1' => $advertiserObj->getTel1(),
                    'tel2' => $advertiserObj->getTel2(),
                    'tel3' => $advertiserObj->getTel3(),
                    'email' => $advertiserObj->getEmail(),
                    'url' => $advertiserObj->getUrl(),
                    'location' => $advertiserObj->generateLocation()
                ] : null,
                'message' => $advertiserObj !== null ? '' : 'Datele de contact nu au fost gasite',
            ]);
        } else {
            return new JsonModel([
                'error' => 1,
                'result' =>  null,
                'message' => 'Datele de contact nu au fost gasite2',
            ]);
        }
    }

    public function cronInactivateOldAdsAction()
    {
        $adCollection = new AdCollection($this);
        $adCollection->inactivateExpiredAds(10);
    }
}

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
//        require_once 'mandrill-api-php/src/Mandrill.php'; //Not required with Composer
        /*$mandrill = new \Mandrill('rQUJH3gglPTL_T4BmX6IuQ');
        try {
            $mandrill = new \Mandrill('rQUJH3gglPTL_T4BmX6IuQ');
            $message = array(
                'html' => '<p>Example HTML content</p>',
                'text' => 'Example text content',
                'subject' => 'example subject',
                'from_email' => 'contact@tirbox.ro',
                'from_name' => 'Example Name',
                'to' => array(
                    array(
                        'email' => 'ileavalentin@gmail.com',
                        'name' => 'Vali Ilea',
                        'type' => 'to'
                    )
                ),
                'headers' => array('Reply-To' => 'contact@tirbox.ro'),
                'important' => false,
                'track_opens' => null,
                'track_clicks' => null,
                'auto_text' => null,
                'auto_html' => null,
                'inline_css' => null,
                'url_strip_qs' => null,
                'preserve_recipients' => null,
                'view_content_link' => null,
                'bcc_address' => 'message.bcc_address@example.com',
                'tracking_domain' => null,
                'signing_domain' => null,
                'return_path_domain' => null,
                'merge' => true,
                'merge_language' => 'mailchimp',
                'global_merge_vars' => array(
                    array(
                        'name' => 'merge1',
                        'content' => 'merge1 content'
                    )
                ),
                'merge_vars' => array(
                    array(
                        'rcpt' => 'recipient.email@example.com',
                        'vars' => array(
                            array(
                                'name' => 'merge2',
                                'content' => 'merge2 content'
                            )
                        )
                    )
                ),
                'tags' => array('password-resets'),
                'subaccount' => 'customer-123',
                'google_analytics_domains' => array('example.com'),
                'google_analytics_campaign' => 'message.from_email@example.com',
                'metadata' => array('website' => 'www.example.com'),
                'recipient_metadata' => array(
                    array(
                        'rcpt' => 'recipient.email@example.com',
                        'values' => array('user_id' => 123456)
                    )
                ),
                'attachments' => array(
                    array(
                        'type' => 'text/plain',
                        'name' => 'myfile.txt',
                        'content' => 'ZXhhbXBsZSBmaWxl'
                    )
                ),
                'images' => array(
                    array(
                        'type' => 'image/png',
                        'name' => 'IMAGECID',
                        'content' => 'ZXhhbXBsZSBmaWxl'
                    )
                )
            );
            $async = false;
            $ip_pool = 'Main Pool';
            $send_at = 'example send_at';
            $result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);
            print_r($result);



        } catch(Mandrill_Error $e) {
            // Mandrill errors are thrown as exceptions
            echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
            // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
            throw $e;
        }*/



        $cars = $this->cars;
        $id = $this->getEvent()->getRouteMatch()->getParam('id', null);

        $this->layout()->js_call .= ' generalObj.cars = '.json_encode($cars).'; ';
        $this->layout()->js_call .= ' generalObj.ad.create("'.$this->url()->fromRoute("home/ad/upload").'"); ';

        $request = $this->getRequest();
        $adCollection = new AdCollection($this);

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
            if ($request->isPost()) {
                $adTmpId = General::getFromSession('adTmpId');
            } else {
                $adTmpId = 'tmp'.rand(10000, 99999);
                General::addToSession('adTmpId', $adTmpId);
            }

            $resourceObj = new Ad();
        }

        $file_path = PUBLIC_IMG_PATH . $this->myPark->getId() . '/ads/'. $adTmpId . '/' ;

        $form = new AdForm();
        $form->setCancelRoute('back');
        $years = $adCollection->getYears();
        $form->create($resourceObj, $this->cars['categories'], $years, null, null, null);

        $form->bind($resourceObj);

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

                $expDate = General::DateTime(null, 'object');
                $expDate->add(new \DateInterval('P30D'));
                $resourceObj
                    ->setCarMake($carMakelId)
                    ->setStatus('ok')
                    ->setImages(serialize($images))
                    ->setViews(0)
                    ->setContactDisplayed(0)
                ;
                $adDM = new AdDM($this->adapter);

                if ($id === null) {
                    $resourceObj->setExpirationDate(General::DateTime($expDate));
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
//        $modelParam = $this->getEvent()->getRouteMatch()->getParam('car_model', '');
        $classParam = $this->getEvent()->getRouteMatch()->getParam('car_class', null);
//        $partMain = $this->getEvent()->getRouteMatch()->getParam('parts_main', '');
        $adParam = $this->getEvent()->getRouteMatch()->getParam('ad_id', '');
        $searchParam = $this->getEvent()->getRouteMatch()->getParam('search', '');


        $searchWords = '';
        $searchYear = '';
        if (strpos($searchParam, ":") !== false) {
            $search = explode(":", $searchParam);
            $searchWords = $search[0];
            $searchYear = $search[1];
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

//        var_dump($cars['model'][$carcategoriesId]); die();


        // detect Ad ID
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
                'searchYear' => $searchYear,
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
            'adView' => $adView,
            'searchValues' => [
                'input' => str_replace("+", " ", $searchWords),
                'year' => $searchYear,
            ],
//            'searchValue' => str_replace("+", " ", $search[0]),
//            'searchYearStart' => ($search[1]),
            'years' => $adCollection->getYears()
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

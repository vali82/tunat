<?php

namespace Application\Controller;

use Application\libs\General;
use Application\Models\Advertiser\AdvertiserDM;
use Application\Models\Cars\CarsCategoriesDM;
use Application\Models\Cars\CarsModelsDM;
use Application\Models\Cars\CarsPartsMainDM;
use Application\Models\Cars\CarsPartsSubDM;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use ZfcBaseTest\Mapper\AbstractDbMapperTest;

class MyAbstractController extends AbstractActionController
{
    /** @var \Application\Models\Advertiser\Advertiser*/
    protected $myAdvertiserObj;
    protected $myUser;
    protected $role;
    protected $adapter;
    protected $cars;
    protected $translator;

    public function onDispatch(MvcEvent $e)
    {
        $route = $this->getEvent()->getRouteMatch();
        $this->translator = $this->getServiceLocator()->get('translator');
        $this->adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $this->role = $this->getServiceLocator()->get('AuthenticatedUserRole');
        $this->myAdvertiserObj = null;
        $this->myUser = null;

        if (isset($_COOKIE['andoridapp']) && $_COOKIE['andoridapp'] == 'v1.0') {
            $this->layout()->setVariable('mobileApp', 'android-'.$_COOKIE['andoridapp']);
        } else {
            $this->layout()->setVariable('mobileApp', false);
        }


        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $this->myUser = $this->getServiceLocator()->get('AuthenticatedUser');

            $this->myAdvertiserObj = $this->getServiceLocator()->get('AdvertiserObj');

            if ($this->myAdvertiserObj->getTel1() === '') {
                $this->flashMessenger()->addInfoMessage(
                    'Va rugam sa va completati datele de mai jos pentru a putea continua!'
                );
                if ($route->getMatchedRouteName() != 'home/myAccount/update' &&
                    $route->getMatchedRouteName() != 'home/logout'
                ) {
                    return $this->redirect()->toRoute('home/myAccount/update');
                }

            }
        }


//        General::echop($this->role);
//        General::echop($this->myAdvertiserObj);

        // layout variables
        $this->layout()->myUser = $this->myUser;
        $this->layout()->js_call = '';
        ////

        // get cars make and models into session
        $cars = General::getFromSession('cars');
        $googleAnalitics = General::getFromSession('googleAnalitics');
        if ($cars === null || 1==1) {
            $carMake = [];
            $carsMakeDM = new CarsCategoriesDM($this->adapter);
            foreach ($carsMakeDM->fetchResultsArray(null, ['ord' => 'ASC']) as $k => $r) {
                $carMake[$r['id']] = $r['category'];
            }
            $carModel = [];
            $carCateg = [];
            $carsModelsDM = new CarsModelsDM($this->adapter);
            foreach ($carsModelsDM->fetchResultsArray(null, ['popular' => 'DESC', 'car_make' => 'ASC']) as $k => $r) {
                //$years = $r['year_start'] > 0 ? $r['year_start'].'-'.$r['year_end'] : 'toate';
                if (!isset($carCateg[$r['category_id']]) || !in_array($r['car_make'], $carCateg[$r['category_id']])) {
                    $carCateg[$r['category_id']][] = $r['car_make'];
                }
                $carModel[$r['category_id']][$r['id']] = [
                    //'model' => $r['model'],
                    'categ' => $r['car_make'],
                    'popular' => $r['popular']
                ];

            }
            /*$partsMain = [];
            $partsMainDM = new CarsPartsMainDM($this->adapter);
            foreach ($partsMainDM->fetchResultsArray() as $k => $r) {
                $partsMain[$r['id']] = $r['category'];
            }*/
            /*$partsSub = [];
            $partsSubDM = new CarsPartsSubDM($this->adapter);
            foreach($partsSubDM->fetchResultsArray() as $k=>$r) {
                $partsSub[$r['categ_id']][] = $r['category'];
            }*/

            $cars = [
                'categories' => $carMake,
                'categ' => $carCateg,
                'model' => $carModel,
//                'partsMain' => $partsMain,
                //'partsSub' => $partsSub,
            ];
            General::addToSession('cars', $cars);

            $states = General::getConfigs($this, 'consts|states');
            General::addToSession('states', $states);

            $googleAnalitics = General::getConfigs($this, 'googleAnalitics');
            General::addToSession('googleAnalitics', $googleAnalitics);
        }
//        General::echop($carModel);
        $this->cars = $cars;
        $this->layout()->setVariables([
            'googleAnalitics' => $googleAnalitics,
            'cars' => $cars,
            'headTitle' => 'Dezmembrari camioane, utilaje contructii si agricole, utilitare, autobuze, remorci',
            'metaDescription' => 'Tirbox.ro este un site ce ofera utilizatorilor inregistrati, fie ei persoane fizice, fie parcuri auto, spatiu de expunere a anunturilor. Prin intermediul platformei noastre de anunturi puteti gasi piese din categoriile urmatoare: camioane, utilitare max 3.5 tone, remorci, utilaje agricole, utilaje constructii sau autobuze. Anunturi gratuite, anunturi piese noi, piese din dezmembrari, piese, second hand.',
            'metaKeywords' => 'piese auto, second hand, dezmembrari, piese camioane, utilitare, remorci, utilaje constructii, autobuze'
        ]);
        ////

        parent::onDispatch($e);
    }

    public function getCars()
    {
        return $this->cars;
    }

    public function getAdapter()
    {
        return $this->adapter;
    }

    public function getMyAdvertiserObj()
    {
        return $this->myAdvertiserObj;
    }

    protected function uploadGetUploaded($user_id, $folder)
    {
        $files = [];
        $path = PUBLIC_IMG_PATH . $user_id . '/' . implode('/', $folder) . '/';
        foreach (glob($path . "*") as $filefound) {
            $x = explode('/', $filefound);
            $filename = $x[count($x) - 1];
            if (strpos($filename, '_') === false) {
                $files[] = array(
                    'deleteType' => "DELETE",
                    'deleteUrl' => $this->url()->fromRoute(
                        ($folder[0] == 'ads' ?
                            'home/ad/upload' :
                            ($folder[0] == 'offers' ?
                                'home/offers/upload' :
                                'home'
                            )
                        ),
                        [
                            'adv_id' => $user_id,
                            'option' => 'delete',
                            'folder' => $user_id . 'x' . implode('x', $folder),
                            'name' => $filename
                        ]
                    ),
                    'name' => $filename,
                    "size" => filesize($path . '/' . $filename),//$info['size'],
                    //"type" => 'image/jpeg',//$info['type'],
                    "url" => General::getSimpleAvatar($user_id . 'x' . implode('x', $folder), $filename, '9999x9999'),
                    "thumbnailUrl" =>
                        General::getSimpleAvatar($user_id . 'x' . implode('x', $folder), $filename, '100x100'),
                );

//                $images[] = $filename;
            }
        }

        $jsonModel = new JsonModel();
        $jsonModel->setVariables(array('files' => $files));
        return $jsonModel;

    }

    protected function uploadImages($user_id, $folder, $allowedExtensions, $maxSize)
    {
        $adapter = new \Zend\File\Transfer\Adapter\Http();

        $path = PUBLIC_IMG_PATH . $user_id . '/';
        if (!is_dir($path)) {
            mkdir($path);
            chmod($path, 0755);
        }
        foreach ($folder as $f) {
            $path .= $f . '/';
            if (!is_dir($path)) {
                mkdir($path);
                chmod($path, 0755);
            }
        }
        $cnt = 0;
        if ($folder[0] == 'ads' || $folder[0] == 'offers') {
            foreach (glob($path . "/*") as $filefound) {
                if (strpos($filefound, '_') === false) {
                    $cnt++;
                }
            }
        }

        foreach ($adapter->getFileInfo() as $file => $info) {
            if ($folder[0] == 'ads' || $folder[0] == 'offers') {
                $cnt++;
                if ($cnt > 5) {
                    $response = $this->getResponse();
                    $response->setStatusCode(403);
                    $response->sendHeaders();
                    return $response;
                }
            }

            if (isset($info['name']) && $info['name'] != '') {
                if (!in_array($info['type'], $allowedExtensions)) {
                    $response = $this->getResponse();
                    $response->setStatusCode(415);
                    $response->sendHeaders();

                    return $response;
                }

                if ($info['size'] > $maxSize) {
                    $response = $this->getResponse();
                    $response->setStatusCode(413);
                    $response->sendHeaders();
                    return $response;
                }

                $name = rand(100, 999) . md5($info['name']);
                copy($info['tmp_name'], $path . $name);
                rename($info['tmp_name'], $path . $name);

                $files = array(
                    'deleteType' => "DELETE",
                    'deleteUrl' => $this->url()->fromRoute(
                        ($folder[0] == 'ads' ?
                            'home/ad/upload' :
                            ($folder[0] == 'offers' ?
                                'home/offers/upload' :
                                'home'
                            )
                        ),
                        [
                            'adv_id' => $user_id,
                            'option' => 'delete',
                            'folder' => $user_id . 'x' . implode('x', $folder),
                            'name' => $name
                        ]
                    ),
                    'name' => $info['name'],
                    "size" => $info['size'],
                    "type" => $info['type'],
                    "url" => General::getSimpleAvatar($user_id . 'x' . implode('x', $folder), $name, '9999x9999'),
                    "thumbnailUrl" =>
                        General::getSimpleAvatar($user_id . 'x' . implode('x', $folder), $name, '100x100'),
                    "nameDisk" => $name
                );
            }
        }

        $jsonModel = new JsonModel();
        $jsonModel->setVariables(array('files' => [$files]));
        return $jsonModel;

    }

    protected function uploadDeleteImages()
    {
        $file_name = $this->getEvent()->getRouteMatch()->getParam('name', 'xxx');
        $folder = $this->getEvent()->getRouteMatch()->getParam('folder', 'yyy');

        // this has been customized to remove only specific images in certain user_id folders
        // you should modify that to your needs

        $file_path = PUBLIC_IMG_PATH . str_replace('x', '/', $folder). '/'. $file_name;
        $success = is_file($file_path) && $file_name[0] !== '.' && unlink($file_path);

        if ($success) {
            foreach (glob($file_path . "_*") as $filefound) {
                @unlink($filefound);
            }
        }
        $jsonModel = new JsonModel();
        $jsonModel->setVariables([$success]);
        return $jsonModel;
    }
}

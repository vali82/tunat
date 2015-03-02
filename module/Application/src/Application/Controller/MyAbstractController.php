<?php

namespace Application\Controller;

use Application\libs\General;
use Application\Models\Cars\CarsMakeDM;
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
    protected $myUser;
    protected $role;
    protected $adapter;
    protected $cars;

    public function onDispatch(MvcEvent $e)
    {
        $this->adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        if($this->zfcUserAuthentication()->hasIdentity()) {
            $this->myUser = $this->getServiceLocator()->get('AuthenticatedUser');
        } else {
            $this->myUser = null;
        }
        $this->role = $this->getServiceLocator()->get('AuthenticatedUserRole');

        // layout variables
        $this->layout()->myUser = $this->myUser;
        $this->layout()->js_call = '';
        ////

        // get cars make and models into session
        $cars = General::getFromSession('cars');
        if ($cars === null) {
            $carMake = [];
            $carsMakeDM = new CarsMakeDM($this->adapter);
            foreach ($carsMakeDM->fetchResultsArray() as $k=>$r) {
                $carMake[$r['id']] = $r['make'];
            }
            $carModel = [];
            $carCateg = [];
            $carsModelsDM = new CarsModelsDM($this->adapter);
            foreach ($carsModelsDM->fetchResultsArray() as $k=>$r) {
                $years = $r['year_start'] > 0 ? $r['year_start'].'-'.$r['year_end'] : 'toate';
                if (!isset($carCateg[$r['car_id']]) || !in_array($r['model_categ'], $carCateg[$r['car_id']])) {
                    $carCateg[$r['car_id']][] = $r['model_categ'];
                }
                $carModel[$r['car_id']][$r['id']] = [
                    'model' => $r['model'] . ' ('.$years.')',
                    'categ' => $r['model_categ'],
                    'popularity' => $r['popularity']
                ];

            }
            $partsMain = [];
            $partsMainDM = new CarsPartsMainDM($this->adapter);
            foreach ($partsMainDM->fetchResultsArray() as $k=>$r) {
                $partsMain[$r['id']] = $r['category'];
            }
            /*$partsSub = [];
            $partsSubDM = new CarsPartsSubDM($this->adapter);
            foreach($partsSubDM->fetchResultsArray() as $k=>$r) {
                $partsSub[$r['categ_id']][] = $r['category'];
            }*/

            $cars = [
                'make' => $carMake,
                'categ' => $carCateg,
                'model' => $carModel,
                'partsMain' => $partsMain,
                //'partsSub' => $partsSub,
            ];
            General::addToSession('cars', $cars);
        }
//        General::echop($cars['model']);
        $this->cars = $cars;
        $this->layout()->cars = $cars;

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


    protected function uploadAdGetUploaded($user_id, $email, $folder)
    {
        if ($user_id && $email) {
            $path = PUBLIC_PATH . $user_id . '/' . implode('/', $folder) . '/';
            foreach (glob($path . "*") as $filefound) {
                $x = explode('/', $filefound);
                $filename = $x[count($x) - 1];
                if (strpos($filename, '_') === false) {
                    $files[] = array(
                        'deleteType' => "DELETE",
                        'deleteUrl' => $this->url()->fromRoute(
                            'home/ad/upload',
                            [
                                'option' => 'delete',
                                'folder' => $user_id . 'x' . implode('x', $folder),
                                'name' => $filename
                            ]
                        ),
                        'name' => $filename,
                        "size" => filesize($path . '/' . $filename),//$info['size'],
                        //"type" => 'image/jpeg',//$info['type'],
                        "url" => General::getSimpleAvatar($user_id . 'x' . implode('x', $folder), $filename, '800x600'),
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

        $response = $this->getResponse();
        $response->setStatusCode(403);
        $response->sendHeaders();
        return $response;
    }

    protected function uploadAdImages($user_id, $email, $folder, $allowedExtensions, $maxSize)
    {
        if ($user_id && $email) {
            $adapter = new \Zend\File\Transfer\Adapter\Http();

            $path = PUBLIC_PATH . $user_id . '/';
            if (!is_dir($path)) {
                mkdir($path);
                chmod($path, '0777');
            }
            foreach ($folder as $f) {
                $path .= $f . '/';
                if (!is_dir($path)) {
                    mkdir($path);
                    chmod($path, '0777');
                }
            }
            foreach ($adapter->getFileInfo() as $file => $info) {
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

                $name =  rand(100, 999).md5($info['name']);
                rename($info['tmp_name'], $path.$name);

                $files = array(
                    'deleteType' => "DELETE",
                    'deleteUrl' => $this->url()->fromRoute(
                        'home/ad/upload',
                        [
                            'option'=>'delete',
                            'folder' => $user_id.'x'.implode('x', $folder),
                            'name' => $name
                        ]
                    ),
                    'name' => $info['name'],
                    "size" => $info['size'],
                    "type" => $info['type'],
                    "url" => General::getSimpleAvatar($user_id.'x'.implode('x', $folder), $name, '800x600'),
                    "thumbnailUrl" =>
                        General::getSimpleAvatar($user_id.'x'.implode('x', $folder), $name, '100x100'),
                );
            }

            $jsonModel = new JsonModel();
            $jsonModel->setVariables(array('files' => [$files]));
            return $jsonModel;
        }

        $response = $this->getResponse();
        $response->setStatusCode(403);
        $response->sendHeaders();
        return $response;
    }

    protected function deleteAdImages($user_id, $email)
    {
        if ($user_id && $email) {
            $file_name = $this->getEvent()->getRouteMatch()->getParam('name', 'xxx');
            $folder = $this->getEvent()->getRouteMatch()->getParam('folder', 'yyy');

            // this has been customized to remove only specific images in certain user_id folders
            // you should modify that to your needs
            $file_path = PUBLIC_PATH . str_replace('x', '/', $folder). '/'. $file_name;
            $success = is_file($file_path) && $file_name[0] !== '.' && unlink($file_path);

            if ($success) {
                foreach (glob($file_path . "_*") as $filefound) {
                    @unlink($filefound);
                }
            }
        }
        $jsonModel = new JsonModel();
        $jsonModel->setVariables([$success]);
        return $jsonModel;
    }
}

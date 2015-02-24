<?php

namespace Application\Controller;

use Application\libs\General;
use Application\Models\Cars\CarsMakeDM;
use Application\Models\Cars\CarsModelsDM;
use Application\Models\Cars\CarsPartsMainDM;
use Application\Models\Cars\CarsPartsSubDM;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
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
            foreach($carsMakeDM->fetchResultsArray() as $k=>$r) {
                $carMake[$r['id']] = $r['make'];
            }
            $carModel = [];
            $carsModelsDM = new CarsModelsDM($this->adapter);
            foreach($carsModelsDM->fetchResultsArray() as $k=>$r) {
                $years = $r['year_start'] > 0 ? $r['year_start'].'-'.$r['year_end'] : 'toate';
                $carModel[$r['car_id']][$r['model_categ']][$r['id']] = [
                    'model' => $r['model'] . ' ('.$years.')',
                    'popularity' => $r['popularity']
                ];
            }
            $partsMain = [];
            $partsMainDM = new CarsPartsMainDM($this->adapter);
            foreach($partsMainDM->fetchResultsArray() as $k=>$r) {
                $partsMain[$r['id']] = $r['category'];
            }
            /*$partsSub = [];
            $partsSubDM = new CarsPartsSubDM($this->adapter);
            foreach($partsSubDM->fetchResultsArray() as $k=>$r) {
                $partsSub[$r['categ_id']][] = $r['category'];
            }*/

            $cars = [
                'make' => $carMake,
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

    protected function upload($user_id, $email, $path)
    {
        if ($user_id && $email) {
            $adapter = new \Zend\File\Transfer\Adapter\Http();
            $user_path = $path;

            if (!is_dir($user_path)) {
                mkdir($user_path);
                chmod($user_path, '0777');
            }
            foreach ($adapter->getFileInfo() as $file => $info) {
                $error = '';

                /*if ($info['size'] > 10000000) {
                    $error = 'size';
                }*/

                if ($error == '') {
                    $name =  rand(100, 999).md5($info['name']).'.jpg';
                    rename($info['tmp_name'], $user_path.$name);

                    $files = array(
                        'deleteType' => "DELETE",
                        'deleteUrl' => $this->url()->fromRoute('home/ad/upload', ['option'=>'delete']),
                        'name' => $info['name'],
                        "size" => $info['size'],
                        "type" => $info['type'],
                        "url" => '/images/1/'.$name,
                        "thumbnailUrl" => '/images/1/'.$name,
                    );

                    $return = [
                        'error' => 0,
                        'message' => ''
                    ];
                } else {
                    $return = [
                        'error' => 1,
                        'message' => ($error == 'size' ? 'Poza prea mare' : 'Eroare')
                    ];
                    $view = new ViewModel();
                    return $view->setTerminal(true);

                }

            }

            header('Pragma: no-cache');
            header('Cache-Control: private, no-cache');
            header('Content-Disposition: inline; filename="files.json"');
            header('X-Content-Type-Options: nosniff');
            header('Vary: Accept');
            echo json_encode(['files'=>[$files]]);

        }
    }

    private function delete($user_id, $email) {
        if ($user_id && $email) {
            $file_name = $this->getRequest()->getParam('files');
            // this has been customized to remove only specific images in certain user_id folders
            // you should modify that to your needs
            $file_path = PUBLIC_PATH . $user_id. '/'. $file_name;
            $success = is_file($file_path) && $file_name[0] !== '.' && unlink($file_path);
        }
        echo json_encode($success);
    }

    public function uploadAction()
    {
        $option = $this->getEvent()->getRouteMatch()->getParam('option', '');

        if ($option == '' && $this->getRequest()->isPost()) {
            // we're using user_id and email here as a way to verify the upload and store the file in a specific directory,
            // you can strip that out for your purposes.
            $this->upload( $this->myUser->getId(), $this->myUser->getEmail() );
        }

        if ($this->getRequest()->isGet()) {
            $this->upload( $this->session->user['id'], $this->session->user['email'] );
        }
        if ($this->getRequest()->isDelete() || $_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $this->delete( $this->myUser->getId(), $this->myUser->getEmail() );
        }
        exit;
    }
}

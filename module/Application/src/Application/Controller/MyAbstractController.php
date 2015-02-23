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
        if ($cars === null || 1==1) {
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
            $partsSub = [];
            $partsSubDM = new CarsPartsSubDM($this->adapter);
            foreach($partsSubDM->fetchResultsArray() as $k=>$r) {
                $partsSub[$r['categ_id']][] = $r['category'];
            }

            $cars = [
                'make' => $carMake,
                'model' => $carModel,
                'partsMain' => $partsMain,
                'partsSub' => $partsSub,
            ];
            General::addToSession('cars', $cars);
        }
//        General::echop($cars['model']);
        $this->cars = $cars;
        $this->layout()->cars = $cars;

        ////



        parent::onDispatch($e);
    }


}

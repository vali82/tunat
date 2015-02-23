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
        $make = $this->cars['make'];
        $partsMain = $this->cars['partsMain'];

        $carburant = General::getConfigs($this, 'consts|carburant');
        $cilindree = General::getConfigs($this, 'consts|cilindree');

        $resourceObj = new Ad();
        $form = new AdForm();
        $form->setCancelRoute('back');
        $form->create($resourceObj, $make, $carburant, $cilindree, $partsMain);


        $request = $this->getRequest();

        General::echop($this->myUser->getId());
        $resourceObj->setUserId($this->myUser->getId());
        General::echop($resourceObj);

        if ($request->isPost()) {
            $filter = new AdFilter();
            $form->setInputFilter($filter->getInputFilter());

            $form->setData($request->getPost());
            if ($form->isValid()) {

                $resourceObj
                    ->setCarModel($form->get('car_model')->getValue())
                    ->setUserId($this->myUser->getId())
                    ->setStatus('pending')
                ;
                $adDM = new AdDM($this->adapter);
                $adDM->createRow($resourceObj);

                $this->redirect()->toRoute('home');
            }
        }

        return [
            'form' => $form
        ];
    }

}

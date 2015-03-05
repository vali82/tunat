<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\libs\General;
use Application\Models\Ads\Ad;
use Application\Models\Ads\AdCollection;
use Application\Models\Ads\AdDM;
use Application\Models\Cars\CarsCollection;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZfcBaseTest\Mapper\AbstractDbMapperTest;

class IndexController extends MyAbstractController
{
    public function indexAction()
    {
        $ad = new AdCollection($this);

        return [
            'adList' => $ad->adListHTML('homepage')
        ];
    }

    public function pieseAction()
    {

        $cars = $this->cars;
        $makeParam = $this->getEvent()->getRouteMatch()->getParam('car_make', '');
        $modelParam = $this->getEvent()->getRouteMatch()->getParam('car_model', '');
        $classParam = $this->getEvent()->getRouteMatch()->getParam('car_class', null);
        $partMain = $this->getEvent()->getRouteMatch()->getParam('parts_main', '');

        $adList = '';
        $carMakeId = null;
        $x = explode('-', $makeParam);
        if (is_array($x) && count($x) > 0) {
            $carMakeId = $x[count($x)-1];
            if (!isset($cars['model'][$carMakeId])) {
                $carMakeId = null;
            }
        }

        if ($carMakeId === null) {
            $this->redirect()->toRoute('home');
        }

        $carCollection = new CarsCollection($this);

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

        $carModelId = null;
        $x = explode('-', $modelParam);
        if (is_array($x) && count($x) > 0) {
            $carModelId = $x[count($x)-1];
            if (!isset($cars['model'][$carMakeId][$carModelId])) {
                $carModelId = null;
            }
        }

        $partMainId = null;
        $x = explode('-', $partMain);
        if (is_array($x) && count($x) > 0) {
            $partMainId = $x[count($x)-1];
            if (!isset($cars['partsMain'][$partMainId])) {
                $partMainId = null;
            }
        }

        $adList = null;
        if ($carModelId !== null && $partMainId !== null) {
            $ad = new AdCollection($this);

            $adList = $ad->adListHTML([
                'car_model' => $carModelId,
                'part_categ' => $partMainId
            ]);
        }




        return [
            'carMakeId' => $carMakeId,
            'class' => $class,
            'models' => $models,
            'carModelId' => $carModelId,
            'partMainId' => $partMainId,
            'breadcrump' => $carCollection->breadcrump($carMakeId, $class, $carModelId, $partMainId),
            'carCollection' => $carCollection,
            'adList' => $adList,

        ];
    }
}

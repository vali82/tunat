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
        $makeParam = $this->getEvent()->getRouteMatch()->getParam('car_make', 0);
        $modelParam = $this->getEvent()->getRouteMatch()->getParam('car_model', 0);
        $partMain = $this->getEvent()->getRouteMatch()->getParam('parts_main', 0);

        $adList = '';
        $title = '';
        $x = explode('-', $makeParam);
        $carMakeId = $x[count($x)-1];

        if (isset($cars['make'][$carMakeId])) {
//            General::echop($carMakeId);

            $x = explode('-', $modelParam);
            $carModelId = $x[count($x)-1];
            if (isset($cars['model'][$carMakeId][$carModelId])) {
//                General::echop($carModelId);

                $x = explode('-', $partMain);
                $partMainId = $x[count($x)-1];
                if (isset($cars['partsMain'][$partMainId])) {
//                    General::echop($partMainId);
                    $title =
                        $cars['make'][$carMakeId] . ' ' . $cars['model'][$carMakeId][$carModelId]['model'] . ' - '.
                        $cars['partsMain'][$partMainId]
                    ;

                    $ad = new AdCollection($this);

                    $adList = $ad->adListHTML([
                        'car_model' => $carModelId,
                        'part_categ' => $partMainId
                    ]);


                } else {
                    $title = $cars['make'][$carMakeId] . ' ' . $cars['model'][$carMakeId][$carModelId]['model'];
                }


            } else {
                $title = $cars['make'][$carMakeId];
            }
        }

        return [
            'title' => $title,
            'adList' => $adList
        ];
    }
}

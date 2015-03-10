<?php

namespace Application\Models\Ads;

use Application\libs\General;
use Application\Models\Autoparks\ParksDM;
use Application\Models\Autoparks\Parks;
use Application\Models\Cars\CarsCollection;

class AdCollection
{
    protected $controller = null;

    public function __construct($controller)
    {
        /** @var $controller \Application\Controller\MyAbstractController*/
        $this->controller = $controller;

    }

    public function adListHTML($param)
    {
        $cars = $this->controller->getCars();
        $carCollection = new CarsCollection($this->controller);

        $partial = $this->controller->getServiceLocator()->get('viewhelpermanager')->get('partial');

        $adDM = new AdDM($this->controller->getAdapter());
        /** @var $ads \Application\Models\Ads\Ad[]|null*/
        $ads = null;
        if ($param == 'homepage') {
            $ads = $adDM->fetchAllDefault(
                ['status' => 'ok'],
                ['id' => 'DESC'],
                [1, 5]
            );
        } else {
            $page = $this->controller->getEvent()->getRouteMatch()->getParam('page', 1);

            $adDM->setPaginateValues(array(
                'page' => $page,
			    'items_per_page' => 1,
//                'order_by' => $order_by,
//                'order_type' => $order_type
            ));
            $ads = $adDM->fetchAllDefault(
                [
                    'status' => 'ok',
                    'car_model' => $param['car_model'],
                    'part_categ' => $param['part_categ']
                ],
                ['id' => 'DESC'],
                [1, 5]
            );
        }

        $content = '';
        if ($ads !== null) {
            foreach ($ads as $ad) {
                $adImg = unserialize($ad->getImages());
                $content.= $partial('application/ad/partials/ad_in_list.phtml',
                    [
                        'imgSrc' => General::getSimpleAvatar(
                            $ad->getParkId() . 'xadsx'.$ad->getId(),
                            (count($adImg) > 0 ? $adImg[0] : ''),
                            '100x100'
                        ),
                        'title' => $ad->getPartName(),
                        'description' => $ad->getDescription(),
                        'car' => $cars['make'][$ad->getCarMake()] . ' ' .
                            $cars['model'][$ad->getCarMake()][$ad->getCarModel()]['model'],
                        'href' =>
                            $carCollection->urlizeAD($ad),
                    ]
                );
            }
            //$this->paginationControl($this->newsletters, 'Sliding', 'admin/partials/list_pagination.phtml', array('route'=>'kindergarten/newsletter/list', 'order_by'=>$this->order_by, 'order_type'=>$this->order_type));
        }
        return $content !== '' ? $content : null;
    }

    public function viewHTML($id)
    {
        $cars = $this->controller->getCars();

        $adDM = new AdDM($this->controller->getAdapter());
        /** @var $adObj \Application\Models\Ads\Ad*/
        $adObj = $adDM->fetchOne($id);
        if ($adObj !== null) {

            $parkDM = new ParksDM($this->controller->getAdapter());
            /** @var $parkObj \Application\Models\Autoparks\Park*/
            $parkObj = $parkDM->fetchOne($adObj->getParkId());
//            $parkDM->fetchOne($adObj->getU)

            $partial = $this->controller->getServiceLocator()->get('viewhelpermanager')->get('partial');

            $adImgs = unserialize($adObj->getImages());

            return $partial(
                'application/ad/view-ad.phtml',
                [
                    'imgSrc' => General::getSimpleAvatar(
                        $adObj->getParkId() . 'xadsx'.$adObj->getId(),
                        (count($adImgs) > 0 ? $adImgs[0] : ''),
                        '300x300'
                    ),
                    'imgSrcBig' => General::getSimpleAvatar(
                        $adObj->getParkId() . 'xadsx'.$adObj->getId(),
                        (count($adImgs) > 0 ? $adImgs[0] : ''),
                        '2000x2000'
                    ),
                    'images' => $adImgs,
                    'id' => $adObj->getId(),
                    'folder' => $adObj->getParkId() . 'xadsx'.$adObj->getId(),
                    'title' => $adObj->getPartName(),
                    'description' => $adObj->getDescription(),
                    'href' => '#',
                    'motorizare' => ($adObj->getCarCilindree() !== '' ? $adObj->getCarCilindree() . ' ' : '') .
                        ($adObj->getCarCarburant() != '' ? $adObj->getCarCarburant() : 'oricare'),
                    'car' => [
                        'make' => $cars['make'][$adObj->getCarMake()],
                        'model' =>  $cars['model'][$adObj->getCarMake()][$adObj->getCarModel()]['model'],
                        'class' => $cars['model'][$adObj->getCarMake()][$adObj->getCarModel()]['categ']
                    ],
                    'park' => [
                        'name' => $parkObj->getName(),
                        'tel1' => $parkObj->getTel1(),
                        'email' => $parkObj->getEmail(),
                        'url' => $parkObj->getUrl(),
                        'location' => $parkObj->getLocation()
                    ]
                ]
            );

        } else {
            return null;
        }
    }

}
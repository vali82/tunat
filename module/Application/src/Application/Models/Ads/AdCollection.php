<?php

namespace Application\Models\Ads;

use Application\libs\General;

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
                $user_id = 1;
                $adImg = unserialize($ad->getImages());
                $content.= $partial('application/ad/partials/ad_in_list.phtml',
                    [
                        'imgSrc' => General::getSimpleAvatar(
                            $user_id . 'xadsx'.$ad->getId(),
                            (count($adImg) > 0 ? $adImg[0] : ''),
                            '100x100'
                        ),
                        'title' => $ad->getPartName(),
                        'description' => $ad->getDescription(),
                        'car' => $cars['make'][$ad->getCarMake()] . ' ' .
                            $cars['model'][$ad->getCarMake()][$ad->getCarModel()]['model']
                    ]
                );
            }
        }


        return $content;
    }


}
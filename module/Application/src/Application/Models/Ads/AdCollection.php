<?php

namespace Application\Models\Ads;

use Application\libs\General;
use Application\Mail\MailGeneral;
use Application\Models\Autoparks\ParksDM;
use Application\Models\Cars\CarsCollection;
use Application\Models\DataMapper;
use Application\Models\Newsletter\NewsletterCollection;
use Zend\Db\Sql\Predicate\Expression;

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
        $ad_in_list = 'ad_in_list';

        $partial = $this->controller->getServiceLocator()->get('viewhelpermanager')->get('partial');

        $adDM = new AdDM($this->controller->getAdapter());
        /** @var $ads \Application\Models\Ads\Ad[]|null*/
        $ads = null;
        if ($param['place'] == 'homepage') {
            // HOME PAGE ADS
            $ads = $adDM->fetchAllDefault(
                ['status' => 'ok'],
                ['id' => 'DESC'],
                [1, 5]
            );

        } elseif ($param['place'] == 'myAds') {
            // My ADS
            $ad_in_list = 'ad_in_list_to_manage';
            $page = $this->controller->getEvent()->getRouteMatch()->getParam('p', '');
            $partial = $this->controller->getServiceLocator()->get('viewhelpermanager')->get('partial');
            $adDM = new AdDM($this->controller->getAdapter());
            /** @var $ads \Application\Models\Ads\Ad[]|null*/
            $adDM->setPaginateValues(array(
                'page' => $page,
                'items_per_page' => 10,
            ));

            $ads = $adDM->fetchAllDefault(
                [
                    'park_id' => $this->controller->getMyPark()->getId(),
                    'status' => $param['status']
                ],
                ['dateadd' => 'DESC']
            );

        } elseif ($param['place'] == 'onSearch') {
            // AFTER SEARCH ADS

            $page = $this->controller->getEvent()->getRouteMatch()->getParam('p', '');
            $partial = $this->controller->getServiceLocator()->get('viewhelpermanager')->get('partial');
            $adDM = new AdDM($this->controller->getAdapter());

            if (isset($param['search']) && count($param['search']) > 0) {
                $adDM->setColumns(array(
                    '*',
                    'part_name_match' => new Expression(
                        'MATCH (`part_name`) AGAINST ("' . implode(' ', $param['search']) . '" IN BOOLEAN MODE)'
                    ),
                    'description_match' => new Expression(
                        'MATCH (`description`) AGAINST ("' . implode(' ', $param['search']) . '" IN BOOLEAN MODE)'
                    ),
                    'model_match' => new Expression(
                        'MATCH (`car_model`) AGAINST ("' . implode(' ', $param['search']) . '" IN BOOLEAN MODE)'
                    ),
                ));
                $order = array(
                    new Expression(
                        '(model_match * 7 + part_name_match * 5  + description_match) DESC'
                    )
                );
                $sql_where = DataMapper::expression(
                    'MATCH (`part_name`, `description`, car_model) AGAINST ("' . implode(' ', $param['search']) .
                    '" IN BOOLEAN MODE)'
                );
            } else {
                $order = ['id' => 'DESC'];
                $sql_where = null;
            }

            $sql_years = null;
            if ($param['searchYear'] > 0) {
                $x = DataMapper::expression(
                    'year_start <= '.$param['searchYear'].' AND year_end >= '.$param['searchYear']
                );

                $sql_years = [
                    'years_query' => $x
                ];
            }


            /** @var $ads \Application\Models\Ads\Ad[]|null*/
            $adDM->setPaginateValues(array(
                'page' => $page,
                'items_per_page' => 2,
            ));
            $ads = $adDM->fetchAllDefault(
                [
                    'status' => 'ok',
                    'car_make' => $param['carModelId'],
                ] + ($sql_where !== null ? ['search' => $sql_where] : [])
                + ($sql_years !== null ? $sql_years : []),
                $order
            );
        }

        $content = '';
        if ($ads !== null) {
            foreach ($ads as $ad) {
                $adImg = unserialize($ad->getImages());
                $content.= $partial('application/ad/partials/'.$ad_in_list.'.phtml',
                    [
                        'imgSrc' => General::getSimpleAvatar(
                            $ad->getParkId() . 'xadsx'.$ad->getId(),
                            (count($adImg) > 0 ? $adImg[0] : ''),
                            '100x100'
                        ),
                        'title' => $ad->getPartName(),
                        'id' => $ad->getId(),
                        'description' => $ad->getDescription(),
                        'car' => $cars['categories'][$ad->getCarCategory()] . ' ' .
                            $cars['model'][$ad->getCarCategory()][$ad->getCarMake()]['categ'],
                        'model' => $ad->getCarModel(),
                        'yearStart' => $ad->getYearStart(),
                        'yearEnd' => $ad->getYearEnd(),
                        'href' =>
                            $carCollection->urlizeAD($ad),
                        'status' => $ad->getStatus(),
                        'token' => isset($param['token']) ? $param['token'] : '',
                        'views' => $ad->getViews(),
                        'contactDisplayed' => $ad->getContactDisplayed(),
                        'expirationDate' => General::DateTime($ad->getExpirationDate(), 'LONG')
                    ]
                );
            }
        }
        if ($param['place'] !== 'homepage') {
            return [
                'list' => $content !== '' ? $content : null,
                'ads' => $ads
            ];
        } else {
            return $content !== '' ? $content : null;
        }
    }

    public function viewHTML($id)
    {
        $cars = $this->controller->getCars();

        $adDM = new AdDM($this->controller->getAdapter());
        /** @var $adObj \Application\Models\Ads\Ad*/
        $adObj = $adDM->fetchOne([
            'id' => $id,
            //'status' => 'ok'
        ]);
        if ($adObj !== null) {
            // increment view counter
            if ($this->controller->getMyPark() === null ||
                $this->controller->getMyPark()->getId() !== $adObj->getParkId()) {
                $adObj->setViews($adObj->getViews() + 1);
                $adDM->updateRow($adObj);
            }
            ////

            $parkDM = new ParksDM($this->controller->getAdapter());
            /** @var $parkObj \Application\Models\Autoparks\Park*/
            $parkObj = $parkDM->fetchOne($adObj->getParkId());

            $partial = $this->controller->getServiceLocator()->get('viewhelpermanager')->get('partial');

            $adImgs = unserialize($adObj->getImages());

            return [
                $partial(
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
                        'stare' => $adObj->getStare(),
                        'href' => '#',
                        'car' => [
                            'category' => $cars['categories'][$adObj->getCarCategory()],
    //                        'model' =>  $cars['model'][$adObj->getCarMake()][$adObj->getCarModel()]['model'],
                            'class' => $cars['model'][$adObj->getCarCategory()][$adObj->getCarMake()]['categ']
                        ],
                        'park' => [
                            'name' => $parkObj->getName(),
                            'tel1' => $parkObj->getTel1(),
                            'email' => $parkObj->getEmail(),
                            'url' => $parkObj->getUrl(),
                            'location' => $parkObj->generateLocation()
                        ],
                        'status' => $adObj->getStatus()
                    ]
                ),
                $adObj
            ];

        } else {
            return null;
        }
    }

    public function getYears()
    {
        $years = [];
        for ($i=date('Y'); $i>1960; $i--) {
            $years[$i] = $i;
        }
        return $years;
    }

    public function inactivateExpiredAds($limit)
    {
        $adDM = new AdDM($this->controller->getAdapter());
        /** @var $ads \Application\Models\Ads\Ad[]|null*/
        $ads = null;
        $ads = $adDM->fetchAllDefault([
            'status' => 'ok',
            'expiration_date' => DataMapper::expression(
                'expiration_date < "'.General::DateTime().'"'
            )
        ], null, [1, $limit], 'park_id');

        if ($ads !== null) {
            foreach ($ads as $adObj) {
                $parkDM = new ParksDM($this->controller->getAdapter());
                $parkObj = $parkDM->fetchOne($adObj->getParkId());

                // gasire alte anunturi ce trebuie inactivate si trimitere in email
                $ads4thisParkAll = $adDM->fetchAllDefault([
                    'status' => 'ok',
                    'park_id' => $parkObj->getId(),
                    'expiration_date' => DataMapper::expression(
                        'expiration_date < "'.General::DateTime().'"'
                    )
                ]);
                if ($ads4thisParkAll !== null) {
                    $adsInMAil = [];
                    foreach ($ads4thisParkAll as $ad4thisPark) {
                        // marcare ad ca expirat
                        $ad4thisPark->setStatus('expired');
                        $adDM->updateRow($ad4thisPark);
                        $adsInMAil[] = [
                            'name' => $ad4thisPark->getPartName()
                        ];
                    }

                    // trimite mail la parc auto cum ca anuntul a fost inactivat
                    if ($parkObj !== null) {
                        $newsletterCollection = new NewsletterCollection($this->controller);
                        $newsletterCollection->sendMail('inactivate_ad', $adsInMAil, $parkObj);
                    }

                }
            }
        }
    }

}

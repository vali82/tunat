<?php

namespace Application\Models\Ads;

use Application\libs\General;
use Application\Mail\MailGeneral;
use Application\Models\Advertiser\AdvertiserDM;
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
        $counties = General::getFromSession('states');

        $partial = $this->controller->getServiceLocator()->get('viewhelpermanager')->get('partial');

        $adDM = new AdDM($this->controller->getAdapter());
        /** @var $ads \Application\Models\Ads\Ad[]|null*/
        $ads = null;
        if ($param['place'] == 'homepage') {
            // HOME PAGE ADS
            // inner join Advertiser
            $adDM->setJoins([
                'advertiser' => [
                    'name' => array('ap' => 'advertiser'),
                    'on' => 'ap.id = ads.advertiser_id',
                    'columns' => array('state_id' => 'state'),
                    'type' => 'inner'
                ]
            ]);
            $ads = $adDM->fetchAllDefault(
                ['status' => 'ok'],
                ['id' => 'DESC'],
                [1, 10]
            );

        } elseif ($param['place'] == 'categoryPage') {
            // HOME PAGE ADS
            // inner join Advertiser
            $adDM->setJoins([
                'advertiser' => [
                    'name' => array('ap' => 'advertiser'),
                    'on' => 'ap.id = ads.advertiser_id',
                    'columns' => array('state_id' => 'state'),
                    'type' => 'inner'
                ]
            ]);
            $ads = $adDM->fetchAllDefault(
                [
                    'status' => 'ok',
                    'car_category' => $param['category']
                ],
                ['id' => 'DESC'],
                [1, 10]
            );

        } elseif ($param['place'] == 'related') {
            // HOME PAGE ADS
            $param['search'] = General::generateQueryWords('pompa de circulare');

            $adDM->setColumns(array(
                '*',
                'part_name_match' => new Expression(
                    'MATCH (`part_name`) AGAINST ("' . implode(' ', $param['search']) . '" IN BOOLEAN MODE)'
                ),
                'description_match' => new Expression(
                    'MATCH (ads.`description`) AGAINST ("' . implode(' ', $param['search']) . '" IN BOOLEAN MODE)'
                ),
                'model_match' => new Expression(
                    'MATCH (`car_model`) AGAINST ("' . implode(' ', $param['search']) . '" IN BOOLEAN MODE)'
                ),
            ));
            $order = array(
                new Expression(
                    'RAND() DESC'
                )
            );
            $sql_where = DataMapper::expression(
                'MATCH (ads.`part_name`, ads.`description`, ads.car_model) AGAINST ("' . implode(' ', $param['search']) .
                '" IN BOOLEAN MODE)'
            );

            // inner join Advertiser
            $adDM->setJoins([
                'advertiser' => [
                    'name' => array('ap' => 'advertiser'),
                    'on' => 'ap.id = ads.advertiser_id',
                    'columns' => array('state_id' => 'state'),
                    'type' => 'inner'
                ]
            ]);
            $ads = $adDM->fetchAllDefault(
                [
                    'status' => 'ok',
                    'car_make' => $param['carModelId'],
                    'search' => $sql_where,
                    'notThisId' => DataMapper::expression(' ads.id <> '.$param['notThisID'])
                ],
                $order,
                [1, 3]
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

            // inner join Advertiser
            $adDM->setJoins([
                'advertiser' => [
                    'name' => array('ap' => 'advertiser'),
                    'on' => 'ap.id = ads.advertiser_id',
                    'columns' => array('state_id' => 'state'),
                    'type' => 'inner'
                ]
            ]);

            if ($param['role'] == 'contentmanager') {
                $where = [
                    'status' => $param['status']
                ];
            } else {
                $where = [
                    'advertiser_id' => $this->controller->getMyAdvertiserObj()->getId(),
                    'status' => $param['status']
                ];
            }


            $ads = $adDM->fetchAllDefault(
                $where,
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
                        'MATCH (ads.`description`) AGAINST ("' . implode(' ', $param['search']) . '" IN BOOLEAN MODE)'
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
                    'MATCH (ads.`part_name`, ads.`description`, ads.car_model) AGAINST ("' . implode(' ', $param['search']) .
                    '" IN BOOLEAN MODE)'
                );
            } else {
                $order = ['id' => 'DESC'];
                $sql_where = null;
            }

            $sql_years = null;
            $sql_county = null;
            $sql_stare = null;
            $sql_oem = null;
            $sql_advid = null;
            if ($param['searchYear'] != '') {
                $x = DataMapper::expression(
                    'year_start <= '.$param['searchYear'].' AND year_end >= '.$param['searchYear']
                );

                $sql_years = [
                    'years_query' => $x
                ];
            }
            if ($param['searchCounty'] > 0) {
                $sql_county = [
                    'county_query' => DataMapper::expression(
                        'ap.state = '.(int)$param['searchCounty']
                    )
                ];
            }
            if ($param['searchAdvertiser'] > 0) {
                $sql_advid = [
                    'advid_query' => DataMapper::expression(
                        'ads.advertiser_id = '.(int)$param['searchAdvertiser']
                    )
                ];
            }
            if ($param['searchStare'] != '') {
                $sql_stare = [
                    'stare_query' => DataMapper::expression(
                        'ads.stare = "'.$param['searchStare'].'"'
                    )
                ];
            }
            if ($param['searchOem'] != '') {
                $sql_oem = [
                    'oem_query' => DataMapper::expression(
                        'ads.code_oem = "'.$param['searchOem'].'"'
                    )
                ];
            }

            // inner join Advertiser
            $adDM->setJoins([
                'advertiser' => [
                    'name' => array('ap' => 'advertiser'),
                    'on' => 'ap.id = ads.advertiser_id',
                    'columns' => array('state_id' => 'state'),
                    'type' => 'inner'
                ]
            ]);

            /** @var $ads \Application\Models\Ads\Ad[]|null*/
            $adDM->setPaginateValues(array(
                'page' => $page,
                'items_per_page' => 12,
            ));
            $ads = $adDM->fetchAllDefault(
                [
                    'status' => 'ok',
                ]
                + ($param['carModelId'] > 0 ? ['car_make' => $param['carModelId']] : [])
                + ($sql_oem !== null ? $sql_oem : [])
                + ($sql_where !== null ? ['search' => $sql_where] : [])
                + ($sql_years !== null ? $sql_years : [])
                + ($sql_stare !== null ? $sql_stare : [])
                + ($sql_county !== null ? $sql_county : [])
                + ($sql_advid !== null ? $sql_advid : []),
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
                            $ad->getAdvertiserId() . 'xadsx'.$ad->getId(),
                            (count($adImg) > 0 ? $adImg[0] : ''),
                            '130x130',
                            $carCollection->getUrlize($ad->getPartName(). ' ' . $cars['model'][$ad->getCarCategory()][$ad->getCarMake()]['categ'] . ' ' . $ad->getCarModel()) . '-1.jpg'
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
                        'county' => $counties[$ad->getStateId()],
                        'price' =>
                            ($ad->getPrice() == round($ad->getPrice()) ? round($ad->getPrice()) : $ad->getPrice()) .
                            ' ' . $ad->getCurrency(),
                        'stare' => $ad->getStare(),
                        'carCategory' => $ad->getCarCategory(),
                        'urlFilter1' => $carCollection->urlizeCarClass($ad->getCarCategory(), $cars['model'][$ad->getCarCategory()][$ad->getCarMake()]['categ']),
                        'urlFilter2' => $carCollection->urlizeSearchAds($ad->getCarCategory(), $ad->getCarMake())
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
        $carCollection = new CarsCollection($this->controller);
        $adDM = new AdDM($this->controller->getAdapter());



        /** @var $adObj \Application\Models\Ads\Ad*/
        $adObj = $adDM->fetchOne([
            'id' => $id,
            //'status' => 'ok'
        ]);
        if ($adObj !== null) {
            // increment view counter
            if ($this->controller->getMyAdvertiserObj() === null ||
                $this->controller->getMyAdvertiserObj()->getId() !== $adObj->getAdvertiserId()) {
                $adObj->setViews($adObj->getViews() + 1);
                $adDM->updateRow($adObj);
            }
            ////

            $advertiserDM = new AdvertiserDM($this->controller->getAdapter());
            /** @var $advertiserObj \Application\Models\Advertiser\Advertiser*/
            $advertiserObj = $advertiserDM->fetchOne($adObj->getAdvertiserId());

            $partial = $this->controller->getServiceLocator()->get('viewhelpermanager')->get('partial');

            $adImgs = unserialize($adObj->getImages());

            return [
                $partial(
                    'application/ad/view-ad.phtml',
                    [
                        'images' => $adImgs,
                        'id' => $adObj->getId(),
                        'folder' => $adObj->getAdvertiserId() . 'xadsx'.$adObj->getId(),
                        'title' => $adObj->getPartName(),
                        'titleUrlized' => $carCollection->getUrlize($adObj->getPartName(). ' ' . $cars['model'][$adObj->getCarCategory()][$adObj->getCarMake()]['categ'] . ' ' . $adObj->getCarModel()),
                        'description' => $adObj->getDescription(),
                        'descriptionShort' => strlen($adObj->getDescription()) > 200 ? substr($adObj->getDescription(), 0, 200) : '',
                        'stare' => $adObj->getStare(),
                        'href' => '#',
                        'views' => $adObj->getViews(),
                        'refreshDate' => General::DateTime($adObj->getUpdatedAt(), 'LONG'),
                        'years' => $adObj->getYearStart() . ' - ' . $adObj->getYearEnd(),
                        'price' => ($adObj->getPrice() == round($adObj->getPrice()) ? round($adObj->getPrice()) : $adObj->getPrice()) .
                            ' ' . $adObj->getCurrency(),
                        'car' => [
                            'category' => $cars['categories'][$adObj->getCarCategory()],
    //                        'model' =>  $cars['model'][$adObj->getCarMake()][$adObj->getCarModel()]['model'],
                            'class' => $cars['model'][$adObj->getCarCategory()][$adObj->getCarMake()]['categ'],
                            'model' => $adObj->getCarModel(),
                            'oem' => $adObj->getCodeOem(),
                            'categoryID' => $adObj->getCarCategory(),
                            'classUrlized' => strtolower($carCollection->getUrlize($cars['model'][$adObj->getCarCategory()][$adObj->getCarMake()]['categ']))
                        ],
                        'advertiser' => [
                            'id' => $advertiserObj->getId(),
                            'name' => $advertiserObj->getName(),
                            'tel1' => $advertiserObj->getTel1(),
                            'tel2' => $advertiserObj->getTel2(),
                            'tel3' => $advertiserObj->getTel3(),
                            'logo' => $advertiserObj->getLogo(),
                            'folderLogo' => $adObj->getAdvertiserId() . 'xlogo',
                            'email' => $advertiserObj->getEmail(),
                            'url' => str_replace('http://', '', $advertiserObj->getUrl()),
                            'location' => $advertiserObj->generateLocation(),
                            'accountType' => $advertiserObj->getAccountType()
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
        ], null, [1, $limit], 'advertiser_id');

        if ($ads !== null) {
            foreach ($ads as $adObj) {
                $advertiserDM = new AdvertiserDM($this->controller->getAdapter());
                $advertiserObj = $advertiserDM->fetchOne($adObj->getAdvertiserId());

                // gasire alte anunturi ce trebuie inactivate si trimitere in email
                $ads4thisAdvertiserAll = $adDM->fetchAllDefault([
                    'status' => 'ok',
                    'advertiser_id' => $advertiserObj->getId(),
                    'expiration_date' => DataMapper::expression(
                        'expiration_date < "'.General::DateTime().'"'
                    )
                ]);
                if ($ads4thisAdvertiserAll !== null) {
                    $adsInMAil = [];
                    foreach ($ads4thisAdvertiserAll as $ad4thisAdvertiser) {
                        $adImgs = unserialize($ad4thisAdvertiser->getImages());
                        // marcare ad ca expirat
                        $ad4thisAdvertiser->setStatus('inactive');
                        $adDM->updateRow($ad4thisAdvertiser);
                        $adsInMAil[] = [
                            'name' => $ad4thisAdvertiser->getPartName(),
                            'photo' => General::getSimpleAvatar(
                                $ad4thisAdvertiser->getAdvertiserId() . 'xadsx'.$ad4thisAdvertiser->getId(),
                                (count($adImgs) > 0 ? $adImgs[0] : ''),
                                '130x130'
                            )
                        ];
                    }

                    // trimite mail la parc auto cum ca anuntul a fost inactivat
                    if ($advertiserObj !== null) {
                        $newsletterCollection = new NewsletterCollection($this->controller);
                        $newsletterCollection->sendMail('inactivate_ad', $adsInMAil, $advertiserObj);
                    }

                }
            }
        }
    }

}

<?php

namespace Application\Models\Cars;

use Application\libs\General;

class CarsCollection
{
    protected $controller = null;

    public function __construct($controller)
    {
        /** @var $controller \Application\Controller\MyAbstractController*/
        $this->controller = $controller;

    }

    public function urlizeCarClass($categoryId, $categ)
    {
        $cars = $this->controller->getCars();
        $carcategories = strtolower($this->urlize($cars['categories'][$categoryId]));
        return $this->controller->url()->fromRoute(
            'home/piese',
            [
                'categories' => $carcategories,
                'car_class' => $this->urlize($categ),
            ]
        );
    }

    /*public function urlizeCarModel($categoryId, $modelId)
    {
        $cars = $this->controller->getCars();

        $model = $cars['model'][$categoryId][$modelId]['model'];
        $categ = $cars['model'][$categoryId][$modelId]['categ'];
        $carcategories = strtolower($this->urlize($cars['categories'][$categoryId]));

        return $this->controller->url()->fromRoute(
            'home/piese',
            [
                'categories' => $carcategories,
                'car_class' => $this->urlize($categ),
                'car_model' => $this->urlize($model).'-'.$modelId
            ]
        );
    }*/

    public function urlizePartMain($categoryId, $modelId, $partId)
    {
        $cars = $this->controller->getCars();

        $carcategories = strtolower($this->urlize($cars['categories'][$categoryId]));
        $model = $cars['model'][$categoryId][$modelId]['model'];
        $categ = $cars['model'][$categoryId][$modelId]['categ'];
        $part = $cars['partsMain'][$partId];

        return $this->controller->url()->fromRoute(
            'home/piese',
            [
                'categories' => $carcategories,
                'car_class' => $this->urlize($categ),
                'car_model' => $this->urlize($model).'-'.$modelId,
                'parts_main' => $this->urlize($part).'-'.$partId,
            ]
        );
    }

    /**
     * @param \Application\Models\Ads\Ad $ad
     * @return string
     */
    public function urlizeAD($ad, $returnRoute = false)
    {
        $cars = $this->controller->getCars();

        $categoryId = $ad->getCarCategory();
        $modelId = $ad->getCarMake();

//        General::echop($cars['model'][$categoryId]);
//        $partId = $ad->g();

        $carcategories = strtolower($this->urlize($cars['categories'][$categoryId]));
//        $model = $cars['model'][$categoryId][$modelId]['model'];
        $categ = $cars['model'][$categoryId][$modelId]['categ'];
//        $part = $cars['partsMain'][$partId];

        $routeArray = [
            'home/piese',
            [
                'categories' => $carcategories,
                'car_class' => $this->urlize($categ),
                //                'car_model' => $this->urlize($model).'-'.$modelId,
                //                'parts_main' => $this->urlize($part).'-'.$partId,
                //'p' => 1,
                'ad_id' => $this->urlize($ad->getPartName()).'-'.$ad->getId()
            ]
        ];

        if ($returnRoute) {
            return $routeArray;
        }

        return $this->controller->url()->fromRoute(
            $routeArray[0],
            $routeArray[1]
        );
    }

    /**
     * @return string
     */
    public function urlizeSearchAds($categoryId, $modelId)
    {
        $cars = $this->controller->getCars();

        $carcategories = strtolower($this->urlize($cars['categories'][$categoryId]));
//        $model = $cars['model'][$categoryId][$modelId]['model'];
        $categ = $cars['model'][$categoryId][$modelId]['categ'];
//        $part = $cars['partsMain'][$partId];

        return $this->controller->url()->fromRoute(
            'home/piese',
            [
                'categories' => $carcategories,
                'car_class' => $this->urlize($categ),
//                'car_model' => $this->urlize($model).'-'.$modelId,
//                'parts_main' => $this->urlize($part).'-'.$partId,
                'ad_id' => '0',
                'p' => 1,
                'search' => '__search__'
            ]
        );

    }

    public function urlizePageListAds($categoryId, $modelId, $partId)
    {
        $cars = $this->controller->getCars();

        $carcategories = strtolower($this->urlize($cars['categories'][$categoryId]));
//        $model = $cars['model'][$categoryId][$modelId]['model'];
        $categ = $cars['model'][$categoryId][$modelId]['categ'];
//        $part = $cars['partsMain'][$partId];

        return [
            'route'=>'home/piese',
            'routeArray' => [
                'categories' => $carcategories,
                'car_class' => $this->urlize($categ),
//                'car_model' => $this->urlize($model).'-'.$modelId,
//                'parts_main' => $this->urlize($part).'-'.$partId,
                'ad_id' => '0',
                'p' => 1,
            ]
        ];

    }

    private function urlize($name)
    {
        return str_replace(['/', ' ', ','], ['-', '-',''], $name);
    }

    public function getUrlize($name)
    {
        return $this->urlize($name);
    }

    public function urlizeCarcategories($carcategoriesId)
    {
        $cars = $this->controller->getCars();
        $carcategories = strtolower($this->urlize($cars['categories'][$carcategoriesId]));
        return $this->controller->url()->fromRoute(
            'home/piese',
            [
                'categories' => $carcategories,
            ]
        );
    }


    /*public function breadcrump($carcategoriesId, $categ = null, $modelId = null, $partId = null)
    {
        $cars = $this->controller->getCars();
        $carcategories = strtolower($this->urlize($cars['categories'][$carcategoriesId]));


        $model = $part = '';
        if ($modelId !== null) {
            $model = $cars['model'][$carcategoriesId][$modelId]['model'];
        }
        if ($partId !== null) {
            $part = $cars['partsMain'][$partId];
        }

        return
            '<a href="'.$this->controller->url()->fromRoute(
                'home/piese',
                ['categories' => $carcategories]
            ).'">'.$cars['categories'][$carcategoriesId].'</a>' .
            ($categ !== null ?
            ' &gt; <a href="'.$this->urlizeCarClass($carcategoriesId, $categ).'">'.$categ.'</a>' : ''
            ).
            ($modelId !== null ?
                ' &gt; <a href="'.$this->urlizeCarModel($carcategoriesId, $modelId).'">'.$model.'</a>' : ''
            ).
            ($partId !== null ?
                ' &gt; <a href="'.$this->urlizePartMain($carcategoriesId, $modelId, $partId).'">'.$part.'</a>' : ''
            )

            ;
    }*/
}

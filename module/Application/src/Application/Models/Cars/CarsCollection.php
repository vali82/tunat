<?php

namespace Application\Models\Cars;

class CarsCollection
{
    protected $controller = null;

    public function __construct($controller)
    {
        /** @var $controller \Application\Controller\MyAbstractController*/
        $this->controller = $controller;

    }

    public function urlizeCarClass($makeId, $categ)
    {
        $cars = $this->controller->getCars();
        $carMake = strtolower($this->urlize($cars['make'][$makeId]) . '-' .$makeId);
        return $this->controller->url()->fromRoute(
            'home/piese',
            [
                'car_make' => $carMake,
                'car_class' => $this->urlize($categ),
            ]
        );
    }

    public function urlizeCarModel($makeId, $modelId)
    {
        $cars = $this->controller->getCars();

        $model = $cars['model'][$makeId][$modelId]['model'];
        $categ = $cars['model'][$makeId][$modelId]['categ'];
        $carMake = strtolower($this->urlize($cars['make'][$makeId]) . '-' .$makeId);

        return $this->controller->url()->fromRoute(
            'home/piese',
            [
                'car_make' => $carMake,
                'car_class' => $this->urlize($categ),
                'car_model' => $this->urlize($model).'-'.$modelId
            ]
        );
    }

    public function urlizePartMain($makeId, $modelId, $partId)
    {
        $cars = $this->controller->getCars();

        $carMake = strtolower($this->urlize($cars['make'][$makeId]) . '-' .$makeId);
        $model = $cars['model'][$makeId][$modelId]['model'];
        $categ = $cars['model'][$makeId][$modelId]['categ'];
        $part = $cars['partsMain'][$partId];

        return $this->controller->url()->fromRoute(
            'home/piese',
            [
                'car_make' => $carMake,
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
    public function urlizeAD($ad)
    {
        $cars = $this->controller->getCars();

        $makeId = $ad->getCarMake();
        $modelId = $ad->getCarModel();
        $partId = $ad->getPartCateg();

        $carMake = strtolower($this->urlize($cars['make'][$makeId]) . '-' .$makeId);
        $model = $cars['model'][$makeId][$modelId]['model'];
        $categ = $cars['model'][$makeId][$modelId]['categ'];
        $part = $cars['partsMain'][$partId];

        return $this->controller->url()->fromRoute(
            'home/piese',
            [
                'car_make' => $carMake,
                'car_class' => $this->urlize($categ),
                'car_model' => $this->urlize($model).'-'.$modelId,
                'parts_main' => $this->urlize($part).'-'.$partId,
                'p' => 1,
                'ad_id' => $this->urlize($ad->getPartName()).'-'.$ad->getId()
            ]
        );
    }

    /**
     * @return string
     */
    public function urlizePageListAds($makeId, $modelId, $partId)
    {
        $cars = $this->controller->getCars();

        $carMake = strtolower($this->urlize($cars['make'][$makeId]) . '-' .$makeId);
        $model = $cars['model'][$makeId][$modelId]['model'];
        $categ = $cars['model'][$makeId][$modelId]['categ'];
        $part = $cars['partsMain'][$partId];

        return [
            'route'=>'home/piese',
            'routeArray' => [
                'car_make' => $carMake,
                'car_class' => $this->urlize($categ),
                'car_model' => $this->urlize($model).'-'.$modelId,
                'parts_main' => $this->urlize($part).'-'.$partId,
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

    public function urlizeCarMake($carMakeId)
    {
        $cars = $this->controller->getCars();
        $carMake = strtolower($this->urlize($cars['make'][$carMakeId]) . '-' .$carMakeId);
        return $this->controller->url()->fromRoute(
            'home/piese',
            [
                'car_make' => $carMake,
            ]
        );
    }


    public function breadcrump($carMakeId, $categ = null, $modelId = null, $partId = null)
    {
        $cars = $this->controller->getCars();
        $carMake = strtolower($this->urlize($cars['make'][$carMakeId]) . '-' .$carMakeId);


        $model = '';
        if ($modelId !== null) {
            $model = $cars['model'][$carMakeId][$modelId]['model'];
        }
        if ($partId !== null) {
            $part = $cars['partsMain'][$partId];
        }

        return
            '<a href="'.$this->controller->url()->fromRoute(
                'home/piese',
                ['car_make' => $carMake]
            ).'">'.$cars['make'][$carMakeId].'</a>' .
            ($categ !== null ?
            ' &gt; <a href="'.$this->urlizeCarClass($carMakeId, $categ).'">'.$categ.'</a>' : ''
            ).
            ($modelId !== null ?
                ' &gt; <a href="'.$this->urlizeCarModel($carMakeId, $modelId).'">'.$model.'</a>' : ''
            ).
            ($partId !== null ?
                ' &gt; <a href="'.$this->urlizePartMain($carMakeId, $modelId, $partId).'">'.$part.'</a>' : ''
            )

            ;
    }
}

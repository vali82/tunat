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

    public function urlizeCarClass($makeId, $categ)
    {
        return $this->controller->url()->fromRoute(
            'home/piese',
            [
                'car_make' => $this->urlizeCarMake($makeId),
                'car_class' => $this->urlize($categ),
            ]
        );
    }

    public function urlizeCarModel($makeId, $modelId)
    {
        $cars = $this->controller->getCars();

        $model = $cars['model'][$makeId][$modelId]['model'];
        $categ = $cars['model'][$makeId][$modelId]['categ'];

        return $this->controller->url()->fromRoute(
            'home/piese',
            [
                'car_make' => $this->urlizeCarMake($makeId),
                'car_class' => $this->urlize($categ),
                'car_model' => $this->urlize($model).'-'.$modelId
            ]
        );
    }

    public function urlizePartMain($makeId, $modelId, $partId)
    {
        $cars = $this->controller->getCars();

        $model = $cars['model'][$makeId][$modelId]['model'];
        $categ = $cars['model'][$makeId][$modelId]['categ'];
        $part = $cars['partsMain'][$partId];

        return $this->controller->url()->fromRoute(
            'home/piese',
            [
                'car_make' => $this->urlizeCarMake($makeId),
                'car_class' => $this->urlize($categ),
                'car_model' => $this->urlize($model).'-'.$modelId,
                'parts_main' => $this->urlize($part).'-'.$partId,
            ]
        );
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

        return strtolower(str_replace(['/', ' ', ','], ['-', '-',''], $cars['make'][$carMakeId]) . '-' .$carMakeId);
    }


    public function breadcrump($carMakeId, $categ = null, $modelId = null, $partId = null)
    {
        $cars = $this->controller->getCars();

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
                ['car_make' => $this->urlizeCarMake($carMakeId)]
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

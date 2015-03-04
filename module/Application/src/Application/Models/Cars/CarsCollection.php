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

    public function urlizeCarClass($categ)
    {
        /*\Application\Models\Cars\CarsCollection::urlizeCarMake(
            $this->layout()->cars['make'][$this->carMakeId]
        )
            \Application\Models\Cars\CarsCollection::urlizeCarClass($categ)*/
        return str_replace(['/', ' ', ','], ['-', '-',''], $categ);
    }

    public function urlizeCarModel($model)
    {
        return str_replace(['/', ' ', ','], ['-', '-',''], $model);
    }

    public function urlizeCarMake($carMakeId)
    {
        $cars = $this->controller->getCars();

        return strtolower(str_replace(['/', ' ', ','], ['-', '-',''], $cars['make'][$carMakeId]) . '-' .$carMakeId);
    }

    public function breadcrump($carMakeId, $categ = null)
    {
        $cars = $this->controller->getCars();

        return
            '<a href="'.$this->controller->url()->fromRoute(
                'home/piese',
                ['car_make' => $this->urlizeCarMake($carMakeId)]
            ).'">'.$cars['make'][$carMakeId].'</a>' .
            ($categ !== null ?
            ' &gt; <a href="'.$this->controller->url()->fromRoute(
                'home/piese',
                [
                    'car_make' => $this->urlizeCarMake($carMakeId),
                    'car_class' => $this->urlizeCarClass($categ)
                ]
            ).'">'.$categ.'</a>' : ''
            )
            ;
    }

}

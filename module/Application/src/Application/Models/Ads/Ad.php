<?php

namespace Application\Models\Ads;

class Ad
{
    /**@var int*/
    protected $id;
    /**@var int*/
    protected $userId;
    /**@var int*/
    protected $partCateg;
    /**@var string*/
    protected $partName;
    /**@var int*/
    protected $carMake;
    /**@var int*/
    protected $carModel;
    /**@var string*/
    protected $carCarburant;
    /**@var string*/
    protected $carCilindree;
    /**@var string*/
    protected $description;
    /**@var float*/
    protected $price;
    /**@var string*/
    protected $status;
    /**@var string*/
    protected $dateadd;
    /**@var string*/
    protected $updatedAt;
    /**@var string*/
    protected $images;



    public function adListHTML($partial, $ads)
    {
        $content = '';
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


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Ad
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param string $images
     * @return Ad
     */
    public function setImages($images)
    {
        $this->images = $images;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return Ad
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return int
     */
    public function getPartCateg()
    {
        return $this->partCateg;
    }

    /**
     * @param int $partCateg
     * @return Ad
     */
    public function setPartCateg($partCateg)
    {
        $this->partCateg = $partCateg;
        return $this;
    }

    /**
     * @return string
     */
    public function getPartName()
    {
        return $this->partName;
    }

    /**
     * @param string $partName
     * @return Ad
     */
    public function setPartName($partName)
    {
        $this->partName = $partName;
        return $this;
    }

    /**
     * @return int
     */
    public function getCarMake()
    {
        return $this->carMake;
    }

    /**
     * @param int $carMake
     * @return Ad
     */
    public function setCarMake($carMake)
    {
        $this->carMake = $carMake;
        return $this;
    }

    /**
     * @return int
     */
    public function getCarModel()
    {
        return $this->carModel;
    }

    /**
     * @param int $carModel
     * @return Ad
     */
    public function setCarModel($carModel)
    {
        $this->carModel = $carModel;
        return $this;
    }

    /**
     * @return string
     */
    public function getCarCarburant()
    {
        return $this->carCarburant;
    }

    /**
     * @param string $carCarburant
     * @return Ad
     */
    public function setCarCarburant($carCarburant)
    {
        $this->carCarburant = $carCarburant;
        return $this;
    }

    /**
     * @return string
     */
    public function getCarCilindree()
    {
        return $this->carCilindree;
    }

    /**
     * @param string $carCilindree
     * @return Ad
     */
    public function setCarCilindree($carCilindree)
    {
        $this->carCilindree = $carCilindree;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Ad
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return Ad
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return Ad
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getDateadd()
    {
        return $this->dateadd;
    }

    /**
     * @param string $dateadd
     * @return Ad
     */
    public function setDateadd($dateadd)
    {
        $this->dateadd = $dateadd;
        return $this;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param string $updatedAt
     * @return Ad
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }





}
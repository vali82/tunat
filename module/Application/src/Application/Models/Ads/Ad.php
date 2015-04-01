<?php

namespace Application\Models\Ads;

class Ad
{
    /**@var int*/
    protected $id;
    /**@var int*/
    protected $parkId;
    /**@var string*/
    protected $partName;
    /**@var int*/
    protected $carMake;
    /**@var int*/
    protected $carCategory;
    /**@var string*/
    protected $carModel;
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
    /**@var string*/
    protected $yearStart;
    /**@var string*/
    protected $yearEnd;
    /**@var string*/
    protected $currency;
    /**@var int*/
    protected $views;
    /**@var int*/
    protected $contactDisplayed;

    /**
     * @return int
     */
    public function getContactDisplayed()
    {
        return $this->contactDisplayed;
    }

    /**
     * @param int $contactDisplayed
     * @return Ad
     */
    public function setContactDisplayed($contactDisplayed)
    {
        $this->contactDisplayed = $contactDisplayed;
        return $this;
    }

    /**
     * @return int
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * @param int $views
     * @return Ad
     */
    public function setViews($views)
    {
        $this->views = $views;
        return $this;
    }

    /**
     * @return string
     */
    public function getCarModel()
    {
        return $this->carModel;
    }

    /**
     * @param string $carModel
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
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return Ad
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return string
     */
    public function getYearStart()
    {
        return $this->yearStart;
    }

    /**
     * @param string $yearStart
     * @return Ad
     */
    public function setYearStart($yearStart)
    {
        $this->yearStart = $yearStart;
        return $this;
    }

    /**
     * @return string
     */
    public function getYearEnd()
    {
        return $this->yearEnd;
    }

    /**
     * @param string $yearEnd
     * @return Ad
     */
    public function setYearEnd($yearEnd)
    {
        $this->yearEnd = $yearEnd;
        return $this;
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
    public function getParkId()
    {
        return $this->parkId;
    }

    /**
     * @param int $parkId
     * @return Ad
     */
    public function setParkId($parkId)
    {
        $this->parkId = $parkId;
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
    public function getCarCategory()
    {
        return $this->carCategory;
    }

    /**
     * @param int $carCategory
     * @return Ad
     */
    public function setCarCategory($carCategory)
    {
        $this->carCategory = $carCategory;
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
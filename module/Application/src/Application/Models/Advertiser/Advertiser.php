<?php

namespace Application\Models\Advertiser;

use Application\libs\General;

class Advertiser
{
    /**@var int*/
    protected $id;
    /**@var string*/
    protected $name;
    /**@var string*/
    protected $email;
    /**@var string*/
    protected $url;
    /**@var string*/
    protected $city;
    /**@var string*/
    protected $state;
    /**@var string*/
    protected $address;
    /**@var string*/
    protected $description;
    /**@var string*/
    protected $tel1;
    /**@var string*/
    protected $tel2;
    /**@var string*/
    protected $tel3;
    /**@var string*/
    protected $logo;
    /**@var string*/
    protected $accountType;

    /**
     * @return string
     */
    public function getAccountType()
    {
        return $this->accountType;
    }

    /**
     * @param string $accountType
     * @return Advertiser
     */
    public function setAccountType($accountType)
    {
        $this->accountType = $accountType;
        return $this;
    }


    /**
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     * @return Advertiser
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Advertiser
     */
    public function setEmail($email)
    {
        $this->email = $email;
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
     * @return Advertiser
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Advertiser
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Advertiser
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return Advertiser
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return Advertiser
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return Advertiser
     */
    public function setState($state)
    {
        $this->state = $state;
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
     * @return Advertiser
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getTel1()
    {
        return $this->tel1;
    }

    /**
     * @param string $tel1
     * @return Advertiser
     */
    public function setTel1($tel1)
    {
        $this->tel1 = $tel1;
        return $this;
    }

    /**
     * @return string
     */
    public function getTel2()
    {
        return $this->tel2;
    }

    /**
     * @param string $tel2
     * @return Advertiser
     */
    public function setTel2($tel2)
    {
        $this->tel2 = $tel2;
        return $this;
    }

    /**
     * @return string
     */
    public function getTel3()
    {
        return $this->tel3;
    }

    /**
     * @param string $tel3
     * @return Advertiser
     */
    public function setTel3($tel3)
    {
        $this->tel3 = $tel3;
        return $this;
    }






    public function generateLocation()
    {
        $states = General::getFromSession('states');
        $x = [$this->getAddress(), $this->getCity(), $states[$this->getState()]];
        return implode(', ', $x);
    }

    public function generateAvatar($size)
    {
        return General::getSimpleAvatar(
            $this->getId() . 'xlogo',
            $this->getLogo(),
            $size
        );
    }
}

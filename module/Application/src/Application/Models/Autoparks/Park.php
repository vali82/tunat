<?php

namespace Application\Models\Autoparks;

use Application\libs\General;

class Park
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

    /**
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     * @return Park
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
     * @return Park
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
     * @return Park
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
     * @return Park
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
     * @return Park
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
     * @return Park
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
     * @return Park
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
     * @return Park
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
     * @return Park
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
     * @return Park
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
     * @return Park
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
     * @return Park
     */
    public function setTel3($tel3)
    {
        $this->tel3 = $tel3;
        return $this;
    }






    public function generateLocation()
    {
        $x = [$this->getAddress(), $this->getCity(), $this->getState()];
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

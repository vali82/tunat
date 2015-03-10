<?php

namespace Application\Models\Autoparks;

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
    protected $location;
    /**@var string*/
    protected $description;
    /**@var string*/
    protected $tel1;
    /**@var string*/
    protected $tel2;
    /**@var string*/
    protected $tel3;

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
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     * @return Park
     */
    public function setLocation($location)
    {
        $this->location = $location;
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
}

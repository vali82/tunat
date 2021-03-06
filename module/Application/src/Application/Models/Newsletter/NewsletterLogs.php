<?php

namespace Application\Models\Newsletter;

class NewsletterLogs
{
    /**@var int*/
    protected $id;
    /**@var string*/
    protected $emailType;
    /**@var int*/
    protected $advertiserId;
    /**@var string*/
    protected $dateadd;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return NewsletterLogs
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmailType()
    {
        return $this->emailType;
    }

    /**
     * @param string $emailType
     * @return NewsletterLogs
     */
    public function setEmailType($emailType)
    {
        $this->emailType = $emailType;
        return $this;
    }

    /**
     * @return int
     */
    public function getAdvertiserId()
    {
        return $this->advertiserId;
    }

    /**
     * @param int $advertiserId
     * @return NewsletterLogs
     */
    public function setAdvertiserId($advertiserId)
    {
        $this->advertiserId = $advertiserId;
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
     * @return NewsletterLogs
     */
    public function setDateadd($dateadd)
    {
        $this->dateadd = $dateadd;
        return $this;
    }


}
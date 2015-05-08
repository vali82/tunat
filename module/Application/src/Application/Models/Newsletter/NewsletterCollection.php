<?php

namespace Application\Models\Newsletter;

use Application\libs\General;
use Application\Mail\MailGeneral;
use Application\Models\DataMapper;

class NewsletterCollection
{
    protected $controller = null;

    public function __construct($controller)
    {
        /** @var $controller \Application\Controller\MyAbstractController*/
        $this->controller = $controller;

    }

    public function sendMail($emailType, $adsInMAil, $parkObj)
    {
        $newsletterLogsDM = new NewsletterLogsDM($this->controller->getAdapter());
        $alreadySentToday = $newsletterLogsDM->fetchOne([
            'park_id' => $parkObj->getId(),
            'email_type' => $emailType,
            'dateadd'   => DataMapper::expression(
                'dateadd like "'.General::DateTime(null, 'iso-short').' %"'
            )
        ]);
        if ($alreadySentToday === null) {
            $newsletterLogObj = new NewsletterLogs();
            $newsletterLogObj
                ->setEmailType($emailType)
                ->setParkId($parkObj->getId())
            ;
            $newsletterLogsDM->createRow($newsletterLogObj);

            if ($emailType === 'inactivate_ad') {
                // send mail
                $mail = new MailGeneral($this->controller->getServiceLocator());
                $mail->_to = $parkObj->getEmail();
                $mail->_no_reply = true;
                $mail->inactivateAd($parkObj->getName(), $adsInMAil);
                ////
            }
        }


    }
}
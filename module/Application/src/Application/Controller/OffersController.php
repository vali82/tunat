<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Forms\ContactForm;
use Application\Forms\Filters\ContactFilter;
use Application\Forms\OffersForm;
use Application\libs\General;
use Application\Mail\MailGeneral;
use Application\Models\Ads\Ad;
use Application\Models\Ads\AdCollection;
use Application\Models\Ads\AdDM;
use Application\Models\Cars\CarsCollection;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZfcBaseTest\Mapper\AbstractDbMapperTest;

class OffersController extends MyAbstractController
{
    public function uploadAction()
    {
        $option = $this->getEvent()->getRouteMatch()->getParam('option', '');

        if ($option == '' && $this->getRequest()->isPost()) {
            return $this->uploadAdImages(
                "general",
                "offers",
                ['offers', General::getFromSession('offerTmpId')],
                ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'],
                2*1024*1024
            );
        }
        if ($this->getRequest()->isGet()) {
            return  $this->uploadAdGetUploaded(
                "general",
                "offers",
                ['offers', General::getFromSession('offerTmpId')]
            );
        }
        if ($this->getRequest()->isDelete() || $_SERVER['REQUEST_METHOD'] == 'DELETE') {
            return $this->deleteAdImages($this->myAdvertiserObj->getId(), $this->myUser->getEmail());
        }
        exit;
    }

    public function createAction()
    {
        $this->layout()->js_call .= ' generalObj.offers.create("'.$this->url()->fromRoute("home/offers/upload").'"); ';

        $request = $this->getRequest();

        // ADD
        if ($request->isPost()) {
            $offerTmpId = General::getFromSession('offerTmpId');
        } else {
            if (General::getFromSession('offerTmpId') == null) {
                $offerTmpId = 'tmp'.rand(10000, 99999);
                General::addToSession('offerTmpId', $offerTmpId);
            } else {
                $offerTmpId = General::getFromSession('offerTmpId');
            }
        }

        $form = new OffersForm();
        $form->create();


        if ($request->isPost()) {
            $filter = new ContactFilter();
            $form->setInputFilter($filter->getInputFilter());

            $form->setData($request->getPost());
            if ($form->isValid()) {
                $mail = new MailGeneral($this->getServiceLocator());
                $mail->_to = 'contact@tirbox.ro';
                $mail->_from = [
                    'email' => $form->get('email')->getValue(),
                    'name' => $form->get('name')->getValue()
                ];
                $mail->_no_reply = false;
                $mail->contact(
                    $form->get('name')->getValue(),
                    $form->get('subject')->getValue(),
                    $form->get('message')->getValue()
                );

                $this->flashMessenger()->addSuccessMessage(
                    $this->translator->translate('Mesajul dvs. a fost trimis! Va vom contacta in cel mai scurt timp posibil!')
                );

                $this->redirect()->toRoute('home/contact');

            }
        } else {
            if ($this->myUser !== null && $this->myAdvertiserObj !== null) {
                $form->populateValues([
                    'name' => $this->myUser->getDisplayName(),
                    'email' => $this->myUser->getEmail(),
                    'phone' => $this->myAdvertiserObj->getTel1()
                ]);
            }
        }
        return [
            'form' => $form
        ];
    }
}

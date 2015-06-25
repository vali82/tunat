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
use Application\Forms\Filters\OffersFilter;
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
            return $this->uploadImages(
                $this->myAdvertiserObj !== null ? $this->myAdvertiserObj->getId() : "0",
                ['offers', General::getFromSession('offerTmpId')],
                ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'],
                2*1024*1024
            );
        }
        if ($this->getRequest()->isGet()) {
            return  $this->uploadGetUploaded(
                $this->myAdvertiserObj !== null ? $this->myAdvertiserObj->getId() : "0",
                ['offers', General::getFromSession('offerTmpId')]
            );
        }
        if ($this->getRequest()->isDelete() || $_SERVER['REQUEST_METHOD'] == 'DELETE') {
            return $this->uploadDeleteImages();
        }
        exit;
    }

    public function createAction()
    {
        $this->layout()->js_call .= ' generalObj.cars = '.json_encode($this->cars).'; ';
        $this->layout()->js_call .= ' generalObj.offers.create("'.$this->url()->fromRoute("home/offers/upload").'"); ';

        $request = $this->getRequest();


        $offerTmpId = General::getFromSession('offerTmpId');

        if ($offerTmpId == null) {
            $offerTmpId = 'tmp'.rand(10000, 99999);
            General::addToSession('offerTmpId', $offerTmpId);
        }

        $form = new OffersForm();
        $form->create($this->cars['categories']);

        if ($request->isPost()) {
            $filter = new OffersFilter();
            $form->setInputFilter($filter->getInputFilter());

            $form->setData($request->getPost());
            if ($form->isValid()) {
                $states = General::getFromSession('states');

                $mail = new MailGeneral($this->getServiceLocator());
                $mail->_to = 'vanzari@tirbox.ro';
                $mail->bcc = 'contact@tirbox.ro';
                $mail->_from = [
                    'email' => $form->get('email')->getValue(),
                    'name' => $form->get('name')->getValue()
                ];
                $mail->_no_reply = false;
                $mail->requestOffer(
                    [
                        'name' => $form->get('name')->getValue(),
                        'phone' => $form->get('phone')->getValue(),
                        'state' => $states[$form->get('state')->getValue()],
                    ],
                    [
                        'category' => $this->cars['categories'][$form->get('car_category')->getValue()],
                        'make' => $this->cars['model'][$form->get('car_category')->getValue()][$form->get('car_make')->getValue()]['categ'],
                        'model' => $form->get('car_model')->getValue(),
                        'year' => $form->get('year_start')->getValue(),
                        'sasiu' => $form->get('sasiu')->getValue(),
                    ],
                    [
                        $_POST['part_name'],
                        $_POST['part_code'],
                        $_POST['part_descr'],
                    ],
                    ['offers', $offerTmpId],
                    ($this->myAdvertiserObj !== null ? $this->myAdvertiserObj->getId() : "0")
                );

                $this->flashMessenger()->addSuccessMessage(
                    $this->translator->translate('Cererea dvs. a fost trimisa! Va vom contacta in cel mai scurt timp posibil!')
                );


                General::unsetSession('offerTmpId');
                $this->redirect()->toRoute('home/offers/create');

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

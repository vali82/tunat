<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Forms\Filters\MyAccountFilter;
use Application\Forms\MyAccountForm;
use Application\libs\General;
use Application\Models\Ads\AdCollection;
use Application\Models\Ads\AdDM;
use Application\Models\Autoparks\ParksDM;
use Application\Models\Cars\CarsCollection;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;

class MyAccountController extends MyAbstractController
{
    public function indexAction()
    {
        $this->layout()->setVariable('myAccountMenu', [
            'active' => 'myaccount'
        ]);
        return [
            'myPark' => $this->myPark,
        ];
    }

    public function updateAction()
    {
        $this->layout()->setVariable('myAccountMenu', [
            'active' => 'myaccount'
        ]);
        $request = $this->getRequest();
        $error = false;

        $states = General::getFromSession('states');

        $form = new MyAccountForm();
        $form->setCancelRoute('back');
        $form->changeMyAccount($states);
        $form->bind($this->myPark);

        if ($request->isPost()) {
            $filter = new MyAccountFilter();
            $filter->setDbAdapter($this->adapter);
            $filter->setTranslator($this->translator);

            $form->setInputFilter($filter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                if (isset($_FILES['imagefile']) && $_FILES['imagefile']['name'] !== '') {
                    $uploadResponse = $this->uploadAdImages(
                        $this->myPark->getId(),
                        $this->myPark->getEmail(),
                        ['logo'],
                        ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'],
                        2 * 1024 * 1024
                    );
                    if ($uploadResponse instanceof JsonModel) {
                        $file_name = $uploadResponse->getVariable('files')[0]['nameDisk'];
                        $file_path = PUBLIC_IMG_PATH . $this->myPark->getId() . '/logo';
                        foreach (glob($file_path . "/*") as $filefound) {
                            if (strpos($filefound, $file_name) === false) {
                                @unlink($filefound);
                            }
                        }
                        $this->myPark->setLogo($file_name);

                    } else {
                        $form->setMessages(array(
                            'imagefile' => array(
                                $this->translator->translate('A aparut o eroare') . ': ' .
                                $uploadResponse->getReasonPhrase()
                            )
                        ));
                        $error = true;
                    }
                }


                if ($this->myPark->getAccountType() == 1) {
                    if ($form->getInputFilter()->getValue('name') === '') {
                        $form->setMessages(array(
                            'name' => array(
                                $this->translator->translate('A aparut o eroare') . ': ' .
                                $this->translator->translate('Numele este obligatoriu')
                            )
                        ));
                        $error = true;
                    }
                } else {
                    if ($form->getInputFilter()->getValue('name2') === '') {
                        $form->setMessages(array(
                            'name2' => array(
                                $this->translator->translate('A aparut o eroare') . ': ' .
                                $this->translator->translate('Numele este obligatoriu')
                            )
                        ));
                        $error = true;
                    }
                }

                if (!$error) {
                    $DM = new ParksDM($this->adapter);
                    if ($this->myPark->getAccountType() == 1) {
                        $this->myPark->setAccountType('parc-auto');
                    } else {
                        $this->myPark
                            ->setName($form->getInputFilter()->getValue('name2'))
                            ->setUrl('')
                            ->setDescription('')
                        ;
                        $this->myPark->setAccountType('particular');
                    }
                    $DM->updateRow($this->myPark);

                    $this->flashMessenger()->addSuccessMessage(
                        $this->translator->translate('Contul tau a fost modificat cu success')
                    );
                    General::unsetSession('myPark');
                    return $this->redirect()->toRoute('home/myAccount');
                }

            } else {
                $form->populateValues(array(
                    'imagefile' => '<img src="' . $this->myPark->generateAvatar('100x100') . '" />'
                ));
            }

        } else {
            $form->populateValues(array(
                'imagefile' => '<img src="' . $this->myPark->generateAvatar('100x100') . '" />',
                'account_type' => $this->myPark->getAccountType() === 'particular' ? 0 : 1
            ));
        }


        return array(
            'form' => $form
        );

    }
}

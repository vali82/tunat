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
use Zend\View\Model\ViewModel;

class MyAccountController extends MyAbstractController
{
    public function indexAction()
    {
        $this->layout()->setVariable('myAccountMenu', [
            'active' => 'myaccount'
        ]);
        return [
            'myPark' => $this->myPark,
            'myUser' => $this->myUser
        ];
    }

    public function updateAction()
    {
        $token = md5(time().'asdfqwer'.rand(1000, 9999));
        General::addToSession('token', $token);
        $this->layout()->setVariable('myAccountMenu', [
            'active' => 'myaccount'
        ]);
        $urlRemoveLogo = 'confirm(\'Esti sigur?<br />Vrei sa stergi imaginea?\',\'url:' . $this->url()->fromRoute('home/myAccount/removeLogo', ['token' => $token]) . '\');';
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

                    $userDM = $this->getServiceLocator()->get('UserDataMapper');
                    $userDM->findById($this->myUser->getId());
                    $this->myUser->setDisplayName($form->getInputFilter()->getValue('name2'));
                    $userDM->update($this->myUser);

                    $this->flashMessenger()->addSuccessMessage(
                        $this->translator->translate('Contul tau a fost modificat cu success')
                    );
                    General::unsetSession('myPark');
                    General::unsetSession('myUser');
                    return $this->redirect()->toRoute('home/myAccount');
                }

            } else {
                $form->populateValues(array(
                    'imagefile' => '<img src="' . $this->myPark->generateAvatar('100x100') . '" />'.
                        ($this->myPark->getLogo() !== '' ?
                            '<br /><a href="javascript:;" style="display: block; margin-bottom: 10px;" onclick="'.$urlRemoveLogo.'"><span class="glyphicon glyphicon-trash"></span> sterge logo</a>' : '')
                ));
            }

        } else {
            $form->populateValues(array(
                'imagefile' => '<img src="' . $this->myPark->generateAvatar('100x100') . '" />'.
                    ($this->myPark->getLogo() !== '' ?
                        '<br /><a href="javascript:;" style="display: block; margin-bottom: 10px;" onclick="'.$urlRemoveLogo.'"><span class="glyphicon glyphicon-trash"></span> sterge logo</a>' : ''),
                'account_type' => $this->myPark->getAccountType() === 'particular' ? 0 : 1,
                'name2' => $this->myUser->getDisplayName()
            ));
        }


        return array(
            'form' => $form
        );

    }

    public function removeLogoAction()
    {
        $error = 0;
        $messageError = '';
        $messageSuccess = '';
        $token = $this->getEvent()->getRouteMatch()->getParam('token', '');

        if ($token == General::getFromSession('token')) {
            $DM = new ParksDM($this->adapter);

            $this->getEvent()->getRouteMatch()->setParam('name', $this->myPark->getLogo());
            $this->getEvent()->getRouteMatch()->setParam('folder', $this->myPark->getId() . 'xlogo');

            $responseJson = $this->deleteAdImages($this->myPark->getId(), $this->myPark->getEmail());
            $this->myPark
                ->setLogo('')
            ;
            $DM->updateRow($this->myPark);

            $messageSuccess = 'Logo-ul a fost sters';
        } else {
            $messageError = 'Token invalid!';
            $error = 1;
        }

        if ($error) {
            $this->flashMessenger()->addErrorMessage($messageError);
        } else {
            $this->flashMessenger()->addSuccessMessage($messageSuccess);
        }

        $this->redirect()->toRoute('home/myAccount/update');
    }
}

<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Forms\ChangePasswordForm;

use Application\Forms\Filters\ResetPasswordFilter;
use Application\Mail\MailGeneral;

use Application\Libs\General;
use Application\Models;

use Application\Models\Zuser as Zuser;
use Zend\Form\Form as ZendForm;


use Zend\Http\Header\SetCookie;
use Zend\Http\Response;
use Zend\View\Model\JsonModel;

use Zend\View\Model\ViewModel;
use ZfcUser\Service\User as UserService;

class LoginRegisterController extends MyAbstractController
{
    private $loginForm;
    private $registerForm;
    private $userService;
    /** @var \ZfcUser\Mapper\User */
    private $userTable;

    private function getUserTable()
    {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('zfcuser_user_mapper');
        }
        return $this->userTable;
    }

    private function setLoginForm(ZendForm $loginForm)
    {
        $this->loginForm = $loginForm;
        $fm = $this->flashMessenger()->setNamespace('zfcuser-login-form')->getMessages();

        if (isset($fm[0])) {
            $this->loginForm->setMessages(
                array('identity' => array($fm[0]))
            );
        }
        return $this;
    }

    private function getLoginForm()
    {
        if (!$this->loginForm) {
            $this->setLoginForm($this->getServiceLocator()->get('zfcuser_login_form'));
        }
        return $this->loginForm;
    }

    private function setRegisterForm(ZendForm $loginForm)
    {
        $this->registerForm = $loginForm;
        $fm = $this->flashMessenger()->setNamespace('zfcuser-register-form')->getMessages();

        if (isset($fm[0])) {
            $this->registerForm->setMessages(
                array('email' => array($fm[0]))
            );
        }
        return $this;
    }

    private function getRegisterForm()
    {
        if (!$this->registerForm) {
            $this->setRegisterForm($this->getServiceLocator()->get('zfcuser_register_form'));
        }
        return $this->registerForm;
    }

    private function getUserService()
    {
        if (!$this->userService) {
            $this->userService = $this->getServiceLocator()->get('zfcuser_user_service');
        }
        return $this->userService;
    }

    private function setUserService(UserService $userService)
    {
        $this->userService = $userService;
        return $this;
    }

    private function loggedOkUser($userObject)
    {
        $responseJSON = array(
            "error" => 0,
            "result" => [
                'id' => $userObject->getId(),
                'email' => $userObject->getEmail(),
                'redirectUrl' => $this->url()->fromRoute('home/afterlogin'),
            ],
            "message" => $this->translator->translate('Autentificare reusita! Redirectare in contul tau...')
        );
        return new JsonModel($responseJSON);
    }

    public function forgotPasswordAction()
    {
        $email = $_GET['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->flashMessenger()->addErrorMessage($this->translator->translate(
                'Acest email <strong>'.$email.'</strong> este invalid'
            ));
            return $this->redirect()->toRoute('home');
        }

        $userMapper = $this->getUserTable();
        $userObj = $userMapper->findByEmail($email);

        if ($userObj !== false) {
            $hash = rand(1000, 9999) . md5(time() . rand(1000, 9999)) . rand(1000, 9999);
            // update
            $userForgotPassDM = new Zuser\UserForgotPassDM($this->adapter);
            $userForgotPass = $userForgotPassDM->fetchOne([
                'email' => $email
            ]);
            if ($userForgotPass !== null) {
                $userForgotPass->setEmail($email)->setHash($hash);
                $userForgotPassDM->updateRow($userForgotPass);
            } else {
                $userForgotPass = new Zuser\UserForgotPass();
                $userForgotPass->setEmail($email)->setHash($hash);
                $userForgotPassDM->createRow($userForgotPass);
            }

            $mail = new MailGeneral($this->getServiceLocator());
            $mail->_to = $email;
            $mail->_no_reply = true;
            $mail->forgotPassword($userObj->getDisplayName(), $hash);

            $this->flashMessenger()->addSuccessMessage($this->translator->translate(
                'Vei primi un mail cu instructiuni de resetare a parolei!'
            ));


        } else {
            $this->flashMessenger()->addErrorMessage(sprintf($this->translator->translate(
                'Acest email <strong>%s</strong> este invalid'
            )), $email);
        }

        return $this->redirect()->toRoute('home');
    }

    public function resetPasswordAction()
    {
        $hash = $this->getEvent()->getRouteMatch()->getParam('hash');

        $userForgotPassDM = new Zuser\UserForgotPassDM($this->adapter);
        $userForgotPass = $userForgotPassDM->fetchOne(
            ['hash' => $hash]
        );

        $userObj = null;

        if ($userForgotPass !== null) {
            $userDM = $this->getUserTable();
            $userObj = $userDM->findByEmail($userForgotPass->getEmail());
        }


        if ($userObj !== null && $hash != '') {
            $form = new ChangePasswordForm('change-pass');
            $form->resetPass();
            $request = $this->getRequest();

            if ($request->isPost()) {
                $filter = new ResetPasswordFilter();
                $form->setInputFilter($filter->getInputFilter());
                $form->setData($request->getPost());

                if ($form->isValid()) {
                    $newpass = $form->getInputFilter()->getValue('password');

                    $this->getRequest()->getPost()->set('identity', $userObj->getEmail());
                    $this->getRequest()->getPost()->set('credential', $newpass);

                    // update PASS
                    $userForgotPassDM->deleteOne($userForgotPass);
                    $userDataMapper = $this->getServiceLocator()->get('UserDataMapper');
                    $userDataMapper->updateRow($userObj, $newpass);


                    // authenticate
                    $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
                    $this->zfcUserAuthentication()->getAuthService()->clearIdentity();

                    $adapter = $this->zfcUserAuthentication()->getAuthAdapter();
                    $result = $adapter->prepareForAuthentication($this->getRequest());


                    // Return early if an adapter returned a response
                    if ($result instanceof Response) {
                        return $result;
                    }

                    $auth = $this->zfcUserAuthentication()->getAuthService()->authenticate($adapter);

                    if (!$auth->isValid()) {
                        $this->flashMessenger()->addErrorMessage(
                            $this->translator->translate('Invalid Credentials. Authentification failed!')
                        );
                        $adapter->resetAdapters();
                        return $this->redirect()->toRoute('home');
                    } else {
                        General::unsetSession('myAdvertiserObj');
                        General::unsetSession('myUser');
//                        General::unsetSession('AuthenticatedUserRole');
                        $this->flashMessenger()->addSuccessMessage(
                            $this->translator->translate('Parola ta a fost schimbata cu success!')
                        );
                        return $this->redirect()->toRoute('home/ad/myAds');
                    }
                }
            } else {
                $form->populateValues(array('email' => $userObj->getEmail()));
            }

            $view = new ViewModel(array(
                'form' => $form,
                'hash' => $hash,
            ));
            $view->setTemplate('application/index/reset-password');
            return $view;
        } else {
            return $this->redirect()->toRoute('home');
        }
    }

    public function loginAction()
    {
        if (!isset($_POST) || !isset($_POST['data'])) {
            return $this->redirect()->toRoute('home');

        } else {
            $this->data = ($_POST['data']);

            if (isset($this->data['username']) && isset($this->data['password'])) {
                $data = array(
                    'identity' => $this->data['username'],
                    'credential' => $this->data['password'],
                );

                $this->getRequest()->getPost()->set('identity', $data['identity']);
                $this->getRequest()->getPost()->set('credential', $data['credential']);

                $form = $this->getLoginForm();

                $form->setData($data);

                if (!$form->isValid()) {
                    $responseJSON = array(
                        "error" => 1,
                        "result" => null,
                        "message" => $this->translator->translate("Autentificare esuata! Parola si email-ul obligatorii!")
                    );
                } else {
                    $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
                    $this->zfcUserAuthentication()->getAuthService()->clearIdentity();
                    $adapter = $this->zfcUserAuthentication()->getAuthAdapter();
                    $adapter->prepareForAuthentication($this->getRequest());
                    $auth = $this->zfcUserAuthentication()->getAuthService()->authenticate($adapter);

                    if (!$auth->isValid()) {
                        $adapter->resetAdapters();
                        $responseJSON = array(
                            "error" => 1,
                            "result" => null,
                            "message" => $this->translator->translate("Autentificare esuata! Email sau parola invalida!")
                        );
                    } else {
                        $userObject = $this->getUserTable()->findByEmail($data['identity']);

                        // remember me
                        if (isset($this->data['rememberme']) && $this->data['rememberme'] == 1) {
                            $hash = md5('asd'.time() . $userObject->getId());

                            $userObject->setHashLogin($hash);
                            $this->getUserTable()->update($userObject);

                            $cookie = new SetCookie('tbroacc', $hash, time() + 14 * 60 * 60 * 24); // now + 2 weeks
                            $response = $this->getResponse()->getHeaders();
                            $response->addHeader($cookie);
                        }
                        ////
                        return $this->loggedOkUser($userObject);
                    }
                }
            } else {
                $responseJSON = array(
                    "error" => 1,
                    "result" => null,
                    "message" => $this->translator->translate("Autentificare esuata! Parola si email-ul obligatorii!")
                );
            }
            return new JsonModel($responseJSON);
        }
    }

    public function registerAction()
    {
        if (!isset($_POST) || !isset($_POST['data'])) {
            $this->layout('layout/homepage');
            return [];

        } else {
            $data = ($_POST['data']);

            $this->getRequest()->getPost()->set('email', $data['email']);
            $this->getRequest()->getPost()->set('password', $data['password']);
            $this->getRequest()->getPost()->set('passwordVerify', $data['passwordVerify']);

            $form = $this->getRegisterForm();

            $form->setData($data);

            if (!$form->isValid()) {
                $messages = '';
                foreach ($form->getMessages() as $field => $message) {
                    foreach ($message as $m) {
                        if ($messages == '') {
                            $messages = $this->translator->translate($field) . ': '.$m;
                        }
                    }
                }
                $responseJSON = array(
                    "error" => 1,
                    "result" => null,
                    "message" => $this->translator->translate("Eroare la") . ' ' . $messages
                );
            } else {
                $service = $this->getUserService();

                $user = $service->register($data);

                if (!$user) {
                    $responseJSON = array(
                        "error" => 1,
                        "result" => null,
                        "message" => $this->translator->translate("Inregistrare esuata! Va rugam reincercati!")
                    );
                } else {
                    $userObject = $this->getUserTable()->findByEmail($data['email']);
                    $sm = $this->getServiceLocator();
                    $advertiserDM = $sm->get('AdvertiserDM');
                    $advertiser = new Models\Advertiser\Advertiser();
                    $advertiser
                        ->setEmail($userObject->getEmail())
                        ->setName($userObject->getEmail())
                        ->setDescription('')
                        ->setAddress('')
                        ->setCity('')
                        ->setState('')
                        ->setLogo('')
                        ->setAccountType('parc-auto')
                        ->setTel1('')
                        ->setTel2('')
                        ->setTel3('')
                        ->setUrl('')
                    ;
                    $advertiser_id = $advertiserDM->createRow($advertiser);

                    $AdvertiserUserDM = $sm->get('AdvertiserUserDM');
                    $AdvertiserUserDM->createFromArray($userObject->getId(), $advertiser_id);

                    return $this->loggedOkUser($userObject);
                }

            }
            return new JsonModel($responseJSON);
        }
    }

    public function afterLoginAction()
    {
        $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
        if ($auth->hasIdentity()) {
            $user_id = $auth->getIdentity()->getId();
        } else {
            $user_id = 0;
        }

        $userRoleLinkerDM = $this->getServiceLocator()->get('getUserRoleLinkerDB');
        $usrole = $userRoleLinkerDM->fetchByUserId($user_id);

        //if ($usrole['role_id'] !== 'parcauto' || $usrole['role_id'] == 'admin') {
//            General::unsetSession('AuthenticatedUserRole');
            General::unsetSession('myAdvertiserObj');
            General::unsetSession('myUser');
            return $this->redirect()->toRoute('home/ad/myAds');

        /*} else {
            return $this->redirect()->toRoute('home');
        }*/

    }

    public function logoutAction()
    {
        $headers = $this->getResponse()->getHeaders();
        $cookie = $this->getRequest()->getCookie();
        if ($cookie->offsetExists('tbroacc')) {
            $new_cookie= new SetCookie('tbroacc', '');//<---empty value and the same 'name'
            $new_cookie->setExpires((time() - 14 * 60 * 60 * 24));
            $headers->addHeader($new_cookie);
        }

        return $this->redirect()->toRoute('zfcuser/logout');
    }
}

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
use Application\Forms\Filters\ChangePasswordFilter;

use Application\Forms\Filters\ResetPasswordFilter;
use Application\Mail\MailGeneral;

use Application\Libs\General;
use Application\Models;
use Application\Mappers;

use Application\Form;
use Application\Form\Filters;
use Application\Models\Zuser as Zuser;
use Zend\Crypt\Password\Bcrypt;
use Zend\Form\Form as ZendForm;


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

    /*public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        return parent::onDispatch($e);
    }*/

    private function getUserTable()
    {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('UserDataMapper');
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
                //'name' => $userObject->getFirstName() . ' ' . $userObject->getLastName(),
                'email' => $userObject->getEmail(),
                'redirectUrl' => $this->url()->fromRoute('home/ad/myAds'),
                'redirectUrlRegister' => $this->url()->fromRoute('home/myAccount/update'),
            ],
            "message" => $this->translator->translate('Autentificare reusita! Redirectare in contul tau...')
        );
        return new JsonModel($responseJSON);
    }

    public function forgotEmailAction()
    {
        if (self::$role !== 'guest') {
            return $this->redirect()->toRoute('home/explore');
        }

        $this->layout('layout/homepage');

        $form = new Form\LoginForm();
        $form->forgotEmail();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->get('phone')->setMessages(array(0 => $this->translator->translate('Eroare: Nr de telefon nu a fost gasit!')));
        }

        return array(
            'form' => $form
        );
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
            $mail->forgotPassword('', $hash);

            $this->flashMessenger()->addSuccessMessage($this->translator->translate(
                'Vei primi un mail cu instructiuni de resetare a parolei!'
            ));


        } else {
            $this->flashMessenger()->addErrorMessage($this->translator->translate(
                'Acest email <strong>'.$email.'</strong> este invalid'
            ));
        }

        return $this->redirect()->toRoute('home');
    }

    public function resetPasswordAction()
    {
        $hash = $this->getEvent()->getRouteMatch()->getParam('hash');
//        $this->layout('layout/homepage');

//        $this->layout()->cnt_title = $this->translator->translate('Reseteaza Parola');

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
                    $userDataMapper = new Zuser\UserDM($this->adapter);
                    $userDataMapper->updateRow($userObj, $newpass);


                    // authenticate
                    $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
                    $this->zfcUserAuthentication()->getAuthService()->clearIdentity();

                    $adapter = $this->zfcUserAuthentication()->getAuthAdapter();
                    $result = $adapter->prepareForAuthentication($this->getRequest());


                    // Return early if an adapter returned a response
                    if ($result instanceof Response) {
                        //$this->logaction->logAction('users', 0, 'registeruser__err', 'user login after register failed, '.$email);
                        return $result;
                    }

                    $auth = $this->zfcUserAuthentication()->getAuthService()->authenticate($adapter);

                    if (!$auth->isValid()) {
                        //$this->logaction->logAction('users', 0, 'registeruser__err', 'user login after register failed, '.$email);
//						$this->logaction->logAction('error_log', 1, 'index/resetPassword', 'login esuat dupa resetare parola');
                        $this->flashMessenger()->addErrorMessage($this->translator->translate('Invalid Credentials. Authentification failed!'));
                        $adapter->resetAdapters();
                        return $this->redirect()->toRoute('home');
                    } else {
                        General::unsetSession('myPark');
                        General::unsetSession('myUser');
                        General::unsetSession('AuthenticatedUserRole');
                        $this->flashMessenger()->addSuccessMessage($this->translator->translate('Parola ta a fost schimbata cu success!'));
                        return $this->redirect()->toRoute('home');
                    }
                }
            } else {
                $form->populateValues(array('email' => $userObj->getEmail()));
            }

            $view = new ViewModel(array(
                'form' => $form,
                'hash' => $hash,
//				'email' => $userObj->getEmail()
                //'type' => $type
            ));
            $view->setTemplate('application/index/reset-password');
            return $view;
        } else {
//			$this->logaction->logAction('error_log', 0, 'index/resetPassword', 'invalid hash : '.$hash);
            return $this->redirect()->toRoute('home');
        }
    }

    public function loginAction()
    {
        if (!isset($_POST) || !isset($_POST['data'])) {
            return $this->redirect()->toRoute('home/home');
        }

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
                    General::unsetSession('AuthenticatedUserRole');
                    $userObject = $this->getUserTable()->findByEmail($data['identity']);

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
                    $parkDM = $sm->get('AutoParkDM');
                    $autoPark = new Models\Autoparks\Park();
                    $autoPark
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
                    $park_id = $parkDM->createRow($autoPark);

                    $userParkDM = $sm->get('AutoParkUserDM');
                    $userParkDM->createFromArray($userObject->getId(), $park_id);

                    return $this->loggedOkUser($userObject);
                }

            }
            return new JsonModel($responseJSON);
        }
    }

    private function registerFromInvite($inviteObj, $userObject = null)
    {
        $this->user = ($userObject !== null ? $userObject : $this->user);

        if ($inviteObj->getAccountType() == 'parinte') {
            // create user
            $dataMapper = new Mappers\UserDM($this->db_adapter);
            //$userObj = new Models\User();
            $this->user
                ->setLastKindergarten($inviteObj->getKindergartenId());
            $dataMapper->updateRow($this->user);

            $parentDM = new Children\ChildParentDM($this->db_adapter);
            $parentObj = $parentDM->fetchOne($inviteObj->getParentId());

            // set parent user_role
            $dm = new Mappers\UserRoleCustomizedDM($this->db_adapter);
            $dm->updateRow($this->user->getUserId(), 5);

            //delete from invites
            $inviteDM = new Mappers\InviteForRegisterDM($this->db_adapter);
            $inviteDM->deleteOne($inviteObj);

            $parentObj
                ->setEmailInfo($this->user->getEmail())
                ->setUserId($this->user->getUserId())
                ->setProfilePhoto($this->user->getProfilePhoto());
            $parentDM->updateRow($parentObj);

            @copy(
                __DIR__ . '/../../../../../public/upload/avatars/users/' . $this->user->getProfilePhoto(),
                __DIR__ . '/../../../../../public/upload/avatars/parent/' . $this->user->getProfilePhoto()
            );
            @unlink(__DIR__ . '/../../../../../public/upload/avatars/users/' . $this->user->getProfilePhoto());

            General::unsetSession('invite_hash');
            General::unsetSession('myUserObj');
            General::unsetSession('userObj');
            General::unsetSession('kindergarten_logged_in');
            General::unsetSession('another_accounts');

        } elseif ($inviteObj->getAccountType() == 'educator') {
            // create user
            $dataMapper = new Mappers\UserDM($this->db_adapter);
            //$userObj = new Models\User();
            $this->user
                ->setLastKindergarten($inviteObj->getKindergartenId());
            $dataMapper->updateRow($this->user);

            $educatorDM = new Educators\EducatorDM($this->db_adapter);
            $educatorObj = $educatorDM->fetchOne($inviteObj->getParentId());

            // set parent user_role
            $dm = new Mappers\UserRoleCustomizedDM($this->db_adapter);
            $dm->updateRow($this->user->getUserId(), 4);

            //delete from invites
            $inviteDM = new Mappers\InviteForRegisterDM($this->db_adapter);
            $inviteDM->deleteOne($inviteObj);

            $educatorObj
                ->setEmailInfo($this->user->getEmail())
                ->setUserId($this->user->getUserId())
                ->setProfilePhoto($this->user->getProfilePhoto());
            $educatorDM->updateRow($educatorObj);

            @copy(
                __DIR__ . '/../../../../../public/upload/avatars/users/' . $this->user->getProfilePhoto(),
                __DIR__ . '/../../../../../public/upload/avatars/educator/' . $this->user->getProfilePhoto()
            );
            @unlink(__DIR__ . '/../../../../../public/upload/avatars/users/' . $this->user->getProfilePhoto());

            General::unsetSession('invite_hash');
            General::unsetSession('myUserObj');
            General::unsetSession('userObj');
            General::unsetSession('kindergarten_logged_in');
            General::unsetSession('another_accounts');
        }
    }

    public function hashInviteAction()
    {
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $hash = $this->getEvent()->getRouteMatch()->getParam('hash');
        $type = $this->getEvent()->getRouteMatch()->getParam('type');
        $role = self::$role;

        if ($type == 'register') {
            $inviteDM = new Mappers\InviteForRegisterDM($this->db_adapter);
            $inviteObj = $inviteDM->fetchByHash($hash);
            if ($inviteObj !== null) {
                if ($inviteObj->getAccountType() == 'parinte') {
                    // check if child has a valid parent
                    $childDM = new Children\ChildDM($this->db_adapter);
                    $childObj = $childDM->fetchByDBId($inviteObj->getChildId());

                    if ($childObj !== null) {
                        if ($childObj->getParentUserId() == 0) {
                            General::addToSession('invite_hash', $inviteObj);

                            if ($role == 'parinte') {
                                return $this->redirect()->toRoute('kindergarten');

                            } elseif ($role == 'guest') {
                                $kgDM = new KgMappers\KindergartenDM($this->db_adapter);
                                $kgObj = $kgDM->fetchOne($inviteObj->getKindergartenId());

                                General::addToSession(
                                    'invite_hash_details',
                                    array('child' => $childObj, 'kindergarten' => $kgObj)
                                );

                                return $this->redirect()->toRoute('home/register');

                            } elseif ($role == 'user') {
                                $this->registerFromInvite($inviteObj);
                                return $this->redirect()->toRoute('kindergarten');

                            } else {
                                return $this->redirect()->toRoute('kindergarten');
                            }
                        } else {
                            $this->flashMessenger()->addInfoMessage(
                                $this->translator->translate(
                                    $childObj->getFullName() . $this->translator->translate(' este deja asignat parintelui ') .
                                    $childObj->getParentFullName()
                                )
                            );
                            /*if ($role == 'parinte') {

                                $kindergarten_logged_in = General::getFromSession('kindergarten_logged_in');
                                if ($kindergarten_logged_in->getId() != null) {
                                    return $this->redirect()->toRoute(self::$route_prefix.'/home');
                                }
                            }*/
                            return $this->redirect()->toRoute('kindergarten');

                        }
                    } else {
                        $this->flashMessenger()->addErrorMessage(
                            $this->translator->translate('A aparut o eroare! Te rugam sa ne contactezi')
                        );
                        return $this->redirect()->toRoute('kindergarten');
                    }

                } elseif ($inviteObj->getAccountType() == 'educator') {
                    General::addToSession('invite_hash', $inviteObj);

                    if ($role == 'educator') {
                        return $this->redirect()->toRoute('kindergarten');

                    } elseif ($role == 'user') {
                        $this->registerFromInvite($inviteObj);
                        return $this->redirect()->toRoute('kindergarten');

                    } elseif ($role == 'guest') {
                        $kgDM = new KgMappers\KindergartenDM($this->db_adapter);
                        $kgObj = $kgDM->fetchOne($inviteObj->getKindergartenId());

                        General::addToSession('invite_hash_details', array('kindergarten' => $kgObj));

                        return $this->redirect()->toRoute('home/register');

                    } else {
                        return $this->redirect()->toRoute('kindergarten');
                    }

                }
            }
        }

        return $this->redirect()->toRoute('home');
    }

    public function afterLoginAction()
    {
        $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
        if ($auth->hasIdentity()) {
            $user_id = $auth->getIdentity()->getId();
        } else {
            $user_id = 0;
        }

        $DM = new Mappers\UserRoleCustomizedDM($this->db_adapter);
        $usrole = $DM->fetchByUserId($user_id);

        if ($usrole['role_id'] == '6') {
            $inviteObj = General::getFromSession('invite_hash');
            if ($inviteObj !== null) {
                $this->registerFromInvite($inviteObj);
                return $this->redirect()->toRoute('kindergarten');
            }
            return $this->redirect()->toRoute('home/explore');

        } elseif ($usrole['role_id'] == '2' || $usrole['role_id'] == '7' || $usrole['role_id'] == '8') {
            return $this->redirect()->toRoute('adminkp/home');

        } elseif ($usrole['role_id'] == '4') {
            $inviteObj = General::getFromSession('invite_hash');
            if ($inviteObj !== null) {
                return $this->redirect()->toRoute('kindergarten');
            } else {
                return $this->redirect()->toRoute('home/profile/home', ['id'=>'wall']);
            }

        } else {
            return $this->redirect()->toRoute('kindergarten');
        }

    }
}

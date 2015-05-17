<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\libs\General;
use Application\Models\Zuser\ForceLogin;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Validator\AbstractValidator;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $translator = $e->getApplication()->getServiceManager()->get('translator');
        $serviceManager = $e->getApplication()->getServiceManager();
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $config = $serviceManager->get('Config');

        $moduleRouteListener->attach($eventManager);
        AbstractValidator::setDefaultTranslator($translator);

        date_default_timezone_set($config['timezone']);
        \Locale::setDefault($config['translator']['locale']);

        if ((!defined('_CRONJOB_') || _CRONJOB_ == false) && $serviceManager->get('AuthenticatedUserRole') == "guest") {
            // for register via SNC
            $e->getApplication()->getEventManager()->getSharedManager()->attach(
                'ScnSocialAuth\Authentication\Adapter\HybridAuth',
                'registerViaProvider.post',
                function ($e) use ($serviceManager) {
                    // User account object
                    $user = $e->getParam('user');
                    $this->doThingsAfterRegisterSocial($serviceManager, $user);
                }
            );
            ////

            // if cookie remember me
            if (isset($_COOKIE['tbroacc']) && $_COOKIE['tbroacc'] != '') {
                General::unsetSession('AuthenticatedUserRole');
                // gasesc row-ul de user
                $user = $serviceManager->get('UserDataMapper')->findByHashLogin($_COOKIE['tbroacc']);
                // acesta e userObj
                $userObj = $serviceManager->get('zfcuser_user_mapper')->findByEmail($user->getEmail());
                // fortarea autentificarii
                $serviceManager->get('zfcuser_auth_service')->authenticate(new ForceLogin($userObj));
            }
            ////

        }
    }

    protected function doThingsAfterRegisterSocial($serviceManager, $user)
    {

        $hybridAuth = null;
        try {
            $hybridAuth = $serviceManager->get('HybridAuth');
            //
        } catch (\Exception $e) {
            //header('Location: http://'.MAIN_DOMAIN);
            die ('ScnSocialAuth coulnd\'t be initialized');
        }

        // FACEBOOK
        $facebook = $hybridAuth->getAdapter("Facebook");
        if ($facebook->isUserConnected()) {
            $userProfile = $facebook->getUserProfile();

            $userRoleLinkerDM = $serviceManager->get('getUserRoleLinkerDB');
            $userRoleLinkerDM->createRow(array(
                'user_id' => $user->getId(),
                'role_id' => 'parcauto'
            ));

            $parkDM = $serviceManager->get('AutoParkDM');
            $autoPark = new Models\Autoparks\Park();
            $autoPark
                ->setEmail($user->getEmail())
                ->setName($userProfile->firstName.' '.$userProfile->lastName)
                ->setDescription('')
                ->setAddress('')
                ->setCity('')
                ->setState('')
                ->setLogo('')
                ->setAccountType('particular')
                ->setTel1('')
                ->setTel2('')
                ->setTel3('')
                ->setUrl('')
            ;
            $park_id = $parkDM->createRow($autoPark);

            $userParkDM = $serviceManager->get('AutoParkUserDM');
            $userParkDM->createFromArray($user->getId(), $park_id);

            $path = PUBLIC_IMG_PATH . $park_id . '/';
            if (!is_dir($path)) {
                mkdir($path);
                chmod($path, 0755);
            }
            $path .= 'logo/';
            if (!is_dir($path)) {
                mkdir($path);
                chmod($path, 0755);
            }

            $hash = $park_id . '_' . md5(time() . $park_id);
            $content = file_get_contents($userProfile->photoURL);
            file_put_contents(PUBLIC_IMG_PATH . $park_id . '/logo/' . $hash, $content);

            $autoPark->setId($park_id);
            $autoPark->setLogo($hash);
            $parkDM->updateRow($autoPark);

        }
        //
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return include __DIR__ . '/config/services.config.php';
    }

    public function getViewHelperConfig()
    {
        return include __DIR__ . '/config/viewhelper.config.php';
    }
}

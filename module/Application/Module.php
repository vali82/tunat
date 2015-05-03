<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

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

//          if (1==1 ||  (!defined('_CRONJOB_') || _CRONJOB_ == false) && $user === null) {
            // for simple register
        $events = $e->getApplication()->getEventManager()->getSharedManager();
        // Handle login
        /*$events->attach('ZfcUser\Authentication\Adapter\AdapterChain', 'authenticate.success', function($e) use ($serviceManager) {
            $user = $e->getIdentity();
            $this->doThingsAfterRegisterStas($serviceManager, $user);
            // do some stuff
        });*/
//        $auth = $sm->get('zfcuser_auth_service');
        /*$events->attach('ZfcUser\Service\User', 'register.post', function($e) use ($serviceManager) {
            $user = $e->getParam('user');
            $this->doThingsAfterRegisterStas($serviceManager, $user);
            // do some stuff
        });*/
        /*$events->attach('ScnSocialAuth\Authentication\Adapter\HybridAuth', 'registerViaProvider.post', function($e) use ($serviceManager) {
            $user = $e->getParam('user');
            $this->doThingsAfterRegisterStas($serviceManager, $user);
            // do some stuff
        });*/

        if (strpos($_SERVER['REQUEST_URI'], '/user/change-password') === 0) {
            $vm = $e->getViewModel();
            $vm = $e->getViewModel();
            /*$vm->setVariable('myAccountMenu', [
                'active' => 'myaccount'
            ]);*/
        }


    }

    public function doThingsAfterRegisterStas($serviceManager, $user)
    {
        /*$fp = fopen( PUBLIC_IMG_PATH . 'data.txt', 'a');
        fwrite($fp, 'do after register');
        fwrite($fp, '---'."\r\n");
        fclose($fp);*/


//        $userDM = $serviceManager->get('UserDataMapper');

        /*$userRoleLinkerDM = $serviceManager->get('getUserRoleLinkerDB');
        $userRoleLinkerDM->createRow(array(
            'user_id' => 102,
            'role_id' => 'user'
        ));*/

        $parkDM = $serviceManager->get('AutoParkDM');
        $autoPark = new Models\Autoparks\Park();
        $autoPark
            ->setEmail($user->getEmail())
            ->setName($user->getEmail())
            ->setDescription('')
            ->setLocation('')
        ;
        $park_id = $parkDM->createRow($autoPark);

        $userParkDM = $serviceManager->get('AutoParkUserDM');
        $userParkDM->createFromArray($user->getId(), $park_id);


        // in order to save api_key
        //$myuser = $userDM->fetchByDBId($user->getId());
        //$userDM->updateRow($myuser);
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

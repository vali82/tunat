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

        // unlogged users .. mark some fields after simple register or social media register
        // PT USER NEINREGISTRATI
        $user = null;
        $auth = $serviceManager->get('zfcuser_auth_service');
        if ($auth->hasIdentity()) {
            $user = $serviceManager->get('AuthenticatedUser');
        }

        /*$fp = fopen( PUBLIC_IMG_PATH . 'data.txt', 'a');
        fwrite($fp, '---'."\r\n");
        fclose($fp);*/
        /*$userRoleLinkerDM = $serviceManager->get('getUserRoleLinkerDB');
        $userRoleLinkerDM->createRow(array(
            'user_id' => 100,
            'role_id' => 'user'
        ));*/

//        if (1==1 ||  (!defined('_CRONJOB_') || _CRONJOB_ == false) && $user === null) {
            // for simple register
            $e->getApplication()->getEventManager()->getSharedManager()
                ->attach('ZfcUser\Service\User', 'register.post', function ($e) use ($serviceManager) {

                    $userRoleLinkerDM = $serviceManager->get('getUserRoleLinkerDB');
                    $userRoleLinkerDM->createRow(array(
                        'user_id' => 101,
                        'role_id' => 'user'
                    ));

                // User account object
                $user = $e->getParam('user');
                $this->doThingsAfterRegisterStas($serviceManager, $user);
            });
//        }
    }

    public function doThingsAfterRegisterStas($serviceManager, $user)
    {
        /*$fp = fopen( PUBLIC_IMG_PATH . 'data.txt', 'a');
        fwrite($fp, 'do after register');
        fwrite($fp, '---'."\r\n");
        fclose($fp);*/


//        $userDM = $serviceManager->get('UserDataMapper');

        $userRoleLinkerDM = $serviceManager->get('getUserRoleLinkerDB');
        $userRoleLinkerDM->createRow(array(
            'user_id' => 102,
            'role_id' => 'user'
        ));

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

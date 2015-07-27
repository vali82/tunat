<?php

use Zend\ServiceManager\ServiceLocatorInterface;
use Application\View\Helper\UserRole as UserRoleViewHelper;
use Application\View\Helper\User as UserViewHelper;


return array(
    'factories' => array(
        'authenticatedUserRole' => function ($sm) {
            $locator = $sm->getServiceLocator();
            $viewHelper = new UserRoleViewHelper();
            $viewHelper->setProviderInterface($locator->get('BjyAuthorize\Provider\Identity\ProviderInterface'));
            return $viewHelper;
        },
		/* 'Params' => function (ServiceLocatorInterface $helpers) {
       		$services = $helpers->getServiceLocator();
       		$app = $services->get('Application');
       		return new Application\View\Helper\Params($app->getRequest(), $app->getMvcEvent());
 		} */
        'myAdvertiserObj' => function ($sm) {
            $locator = $sm->getServiceLocator();
            $auth = $locator->get('zfcuser_auth_service');
            $user = $auth->getIdentity();
            $dbAdapter = $locator->get('Zend\Db\Adapter\Adapter');
            $dm = new \Application\Models\Advertiser\AdvertiserUsersDM($dbAdapter);
            $advertiserUsers = $dm->fetchResultsArray(['user_id' => $user->getId()]);
            $x = array_values($advertiserUsers)[0];
            $dm = new \Application\Models\Advertiser\AdvertiserDM($dbAdapter);
            $advertiser = $dm->fetchOne($x['advertiser_id']);
//            General::addToSession('myAdvertiserObj', $advertiser !== null ? $advertiser : false);
            return $advertiser;
        }
      
    ),
);
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
      
    ),
);
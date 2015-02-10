<?php
use Application\Mappers\UserDM;

use Kindergartens\Mappers\KindergartenDM;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Mapper\UserHydrator;

use Application\Mappers as Mappers;



return array(
    'factories' => array(

        /*'UserObj' => function (ServiceLocatorInterface $sm) {
            
        	$auth = $sm->get('zfcuser_auth_service');
        	if ($auth->hasIdentity()) {
        		$user_id = $auth->getIdentity()->getId();
        		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        		$dm = new UserDM($dbAdapter);
        		return $dm->fetchByDBId($user_id);
        	} else {
        		return null;
        	}
        },*/
        'AuthenticatedUser' => function (ServiceLocatorInterface $sm) {
        	$auth = $sm->get('zfcuser_auth_service');
      		if ($auth->hasIdentity()) {
    			$user_id = $auth->getIdentity();
			} else {
				$user_id = null;
			}
            return $user_id;
        },
        'AuthenticatedUserRole' => function (ServiceLocatorInterface $sm) {
            $pi = $sm->get('BjyAuthorize\Provider\Identity\ProviderInterface');
            $roles = $pi->getIdentityRoles();
            return array_pop($roles);
        },       
        /* 'AffiliateDataMapper' => function (ServiceLocatorInterface $sm) {
            $dm = new Mappers\AffiliateMapper();

            $dm->setDbAdapter($sm->get('zfcuser_zend_db_adapter'));
            $zfcUserOptions = $sm->get('zfcuser_module_options');
            $entityClass = $zfcUserOptions->getUserEntityClass();
            $dm->setEntityPrototype(new $entityClass);
            $dm->setHydrator(new UserHydrator());
            return $dm;
        }, */

    ),
);
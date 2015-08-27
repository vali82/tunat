<?php

use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Mapper\UserHydrator;
use Application\libs\General;

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

        'getUserRoleLinkerDB' => function (ServiceLocatorInterface $sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $dm = new \Application\Models\UserRoleCustomizedDM($dbAdapter);
            return $dm;
        },

        'AuthenticatedUser' => function (ServiceLocatorInterface $sm) {
            $auth = $sm->get('zfcuser_auth_service');
            if ($auth->hasIdentity()) {
                $user = $auth->getIdentity();
                return $user;
            } else {
                $user = null;
            }
            return $user;
        },

        'AdvertiserObj' => function (ServiceLocatorInterface $sm) {
            $advertiser = General::getFromSession('myAdvertiserObj');
            if ($advertiser === null) {
                $auth = $sm->get('zfcuser_auth_service');
                $user = $auth->getIdentity();
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $dm = new \Application\Models\Advertiser\AdvertiserUsersDM($dbAdapter);
                $advertiserUsers = $dm->fetchResultsArray(['user_id' => $user->getId()]);
                $x = array_values($advertiserUsers)[0];
                $dm = new \Application\Models\Advertiser\AdvertiserDM($dbAdapter);
                $advertiser = $dm->fetchOne($x['advertiser_id']);
                General::addToSession('myAdvertiserObj', $advertiser !== null ? $advertiser : false);
            }
            return $advertiser;
        },

        'AdvertiserUserDM'  => function (ServiceLocatorInterface $sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $dm = new \Application\Models\Advertiser\AdvertiserUsersDM($dbAdapter);
            return $dm;
        },

        'AdvertiserDM' => function (ServiceLocatorInterface $sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $dm = new \Application\Models\Advertiser\AdvertiserDM($dbAdapter);
            return $dm;
        },

        /*'AuthenticatedUserRole' => function (ServiceLocatorInterface $sm) {
//            $role = \Application\libs\General::getFromSession('role');
//            if ($role === null) {
                $pi = $sm->get('BjyAuthorize\Provider\Identity\ProviderInterface');
                $roles = $pi->getIdentityRoles();
                $role = array_pop($roles);
//                \Application\libs\General::addToSession('role', $role);
//            }
            return $role;
        },*/
        'AuthenticatedUserRole' => function (ServiceLocatorInterface $sm) {
//            $x = General::getFromSession('AuthenticatedUserRole');
//            if ($x === null) {
                $pi = $sm->get('BjyAuthorize\Provider\Identity\ProviderInterface');
                $roles = $pi->getIdentityRoles();
                $x = array_pop($roles);
//                General::addToSession('AuthenticatedUserRole', $x);
//            }
            return $x;
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

        'UserDataMapper' => function (ServiceLocatorInterface $sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $dm = new \Application\Models\Zuser\UserDM($dbAdapter);
            return $dm;
        },

    ),
);

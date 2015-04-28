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

        'AutoPark' => function (ServiceLocatorInterface $sm) {
            $park = \Application\libs\General::getFromSession('myPark');
            if ($park === null) {
                $auth = $sm->get('zfcuser_auth_service');
                $user = $auth->getIdentity();
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $dm = new \Application\Models\Autoparks\ParkUsersDM($dbAdapter);
                $parkUsers = $dm->fetchResultsArray(['user_id' => $user->getId()]);
                $x = array_values($parkUsers)[0];
                $dm = new \Application\Models\Autoparks\ParksDM($dbAdapter);
                $park = $dm->fetchOne($x['park_id']);
                \Application\libs\General::addToSession('myPark', $park !== null ? $park : false);
            }
            return $park;
        },

        'AutoParkUserDM'  => function (ServiceLocatorInterface $sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $dm = new \Application\Models\Autoparks\ParkUsersDM($dbAdapter);
            return $dm;
        },

        'AutoParkDM' => function (ServiceLocatorInterface $sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $dm = new \Application\Models\Autoparks\ParksDM($dbAdapter);
            return $dm;
        },

        'AuthenticatedUserRole' => function (ServiceLocatorInterface $sm) {
//            $role = \Application\libs\General::getFromSession('role');
//            if ($role === null) {
                $pi = $sm->get('BjyAuthorize\Provider\Identity\ProviderInterface');
                $roles = $pi->getIdentityRoles();
                $role = array_pop($roles);
//                \Application\libs\General::addToSession('role', $role);
//            }
            return $role;
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
            $dm = new \ZfcUser\Mapper\User();

            $dm->setDbAdapter($sm->get('zfcuser_zend_db_adapter'));
            $zfcUserOptions = $sm->get('zfcuser_module_options');
            $entityClass = $zfcUserOptions->getUserEntityClass();
            $dm->setEntityPrototype(new $entityClass);
            $dm->setHydrator(new UserHydrator());
            return $dm;
        },

    ),
);

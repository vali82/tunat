<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use ZfcBaseTest\Mapper\AbstractDbMapperTest;

class MyAbstractController extends AbstractActionController
{
    protected $myUser;
    protected $role;

    public function onDispatch(MvcEvent $e)
    {
        if($this->zfcUserAuthentication()->hasIdentity()) {
            $this->myUser = $this->getServiceLocator()->get('AuthenticatedUser');

        } else {
            $this->myUser = null;
        }

        $this->role = $this->getServiceLocator()->get('AuthenticatedUserRole');

        $this->layout()->myUser = $this->myUser;

        parent::onDispatch($e);
    }


}

<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\AuthenticationService;
use BjyAuthorize\Provider\Identity\ProviderInterface;

class UserRole extends AbstractHelper
{
    protected $providerInterface = null;

    public function __invoke()
    {
        $pi = $this->getProviderInterface();
        if (is_null($pi)) {
            throw new \Exception('Provider Interface not set');
        }
        $roles = $pi->getIdentityRoles();
//        var_dump($roles);
        $role = array_pop($roles);
        return $role ? $role : 'guest';
    }

    public function setProviderInterface(ProviderInterface $pi) {
        $this->providerInterface = $pi;
        return $this;
    }
    public function getProviderInterface() {
        return $this->providerInterface;
    }
}

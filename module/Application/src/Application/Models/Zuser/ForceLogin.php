<?php

namespace Application\Models\Zuser;

use Zend\Authentication\Result;

class ForceLogin implements \Zend\Authentication\Adapter\AdapterInterface
{
    /** * @var UserInterface */
    protected $user;
    /** * @param $user */

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function authenticate()
    {
        return new \Zend\Authentication\Result(Result::SUCCESS, $this->user->getId());
    }
}

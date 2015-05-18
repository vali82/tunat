<?php

namespace Application\Models\Zuser;

use Application\Mappers;

use ZfcUser\Entity\User as ZfcUserUser;

class User extends ZfcUserUser
{
    protected $hashLogin;

    /**
     * @return mixed
     */
    public function getHashLogin()
    {
        return $this->hashLogin;
    }

    /**
     * @param mixed $hash_login
     * @return User
     */
    public function setHashLogin($hash_login)
    {
        $this->hashLogin = $hash_login;
        return $this;
    }
}

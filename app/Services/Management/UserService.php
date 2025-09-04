<?php

namespace App\Services\Management;

use App\Repositories\Management\UserRepository;

class UserService
{
    /**
     * Create a new class instance.
     */
    protected UserRepository $userRepository;     
    
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository=$userRepository;    
    }
    
    public function getUser($email){
        return $this->userRepository->getUser($email);
    }
}

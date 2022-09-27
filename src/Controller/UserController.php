<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;

class UserController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function getAll(): string
    {
        return json_encode($this->userRepository->findAll());
    }

    public function getOne(string $email): string
    {
        return json_encode($this->userRepository->findByEmail($email));
    }

    public function add(User $user): string
    {
        return json_encode($this->userRepository->addUser($user));
    }

    public function update(User $user): string
    {
        return json_encode($this->userRepository->updateUser($user));
    }

    public function delete(string $email): void
    {
        $this->userRepository->deleteUser($email);
    }
}

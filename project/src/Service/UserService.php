<?php

// src/Service/UserService.php
namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService {
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher) {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    public function createUser(array $data): User {
        $user = new User();
        $user->setLastName($data['last_name']);
        $user->setFirstName($data['first_name']);
        $user->setUsername($data['username']);
        $user->setEmailAddress($data['email_address']);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function getAllUsers(): array {
        $users = $this->entityManager->getRepository(User::class)->findAll();
        $usersArray = [];
        foreach ($users as $user) {
            $usersArray[] = [
                'id' => $user->getId(),
                'lastName' => $user->getLastName(),
                'firstName' => $user->getFirstName(),
                'username' => $user->getUsername(),
                'emailAddress' => $user->getEmailAddress(),
                'status' => $user->getStatus(),
                'registrations' => $user->getRegistrations(),
            ];
        }
        return $usersArray;
    }

    public function updateUser(User $user, array $data): User {

        if (isset($data['last_name'])) {
            $user->setLastName($data['last_name']);
        }
        if (isset($data['first_name'])) {
            $user->setFirstName($data['first_name']);
        }

        if (isset($data['username'])) {
            $user->setUsername($data['username']);
        }
        if (isset($data['email'])) {
            $user->setEmailAddress($data['email']);
        }
        if (isset($data['password'])) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }

        if (isset($data['status'])) {
            $user->setStatus($data['status']);
        }

        $this->entityManager->flush();
        return $user;
    }

    public function getUserById(int $id): ?User {
        return $this->entityManager->getRepository(User::class)->find($id);
    }


}

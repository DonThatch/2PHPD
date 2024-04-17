<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;

class UserController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    // Get the list of all users
    #[Route('/api/users', name: 'users_list', methods: ['GET'])]
    public function list(): Response
    {
        // Get all users
        $users = $this->userRepository->findAll();

        // Return a JSON response
        return $this->json($users);
    }

    // Create a user
    #[Route('/api/users', name: 'user_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        // Decode the JSON request body into an array
        $data = json_decode($request->getContent(), true);

        // Check for the required fields in the JSON data
        if (!isset($data['username'], $data['email'], $data['password'])) {
            return $this->json(['error' => 'Missing required fields'], 400);
        }

        // Create a new user entity
        $user = new User();
        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);

//        // Save the user to the database
//        $entityManager = $this->getDoctrine()->getManager();
//        $entityManager->persist($user);
//        $entityManager->flush();

        // Return a JSON response with the new user's ID
        return $this->json(['id' => $user->getId()]);
    }

    // Get details of a user
    #[Route('/api/users/{id}', name: 'user_show', methods: ['GET'])]
    public function show($id): Response
    {
        // Get the user by ID
        $user = $this->userRepository->find($id);

        // If the user does not exist, return a 404 response
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        // Return a JSON response
        return $this->json($user);
    }

    // Update a user
    #[Route('/api/users/{id}', name: 'user_update', methods: ['PUT'])]
    public function update($id, Request $request): Response
    {
        // Get the user by ID
        $user = $this->userRepository->find($id);

        // If the user does not exist, return a 404 response
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        // Decode the JSON request body into an array
        $data = json_decode($request->getContent(), true);

        // Update the user entity
        if (isset($data['username'])) {
            $user->setUsername($data['username']);
        }
        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }
        if (isset($data['password'])) {
            $user->setPassword($data['password']);
        }

//        // Persist the updated user object
//        $entityManager = $this->getDoctrine()->getManager();
//        $entityManager->flush();

        // Return a JSON response
        return $this->json($user);
    }

    // Delete a user
    #[Route('/api/users/{id}', name: 'user_delete', methods: ['DELETE'])]
    public function delete($id): Response
    {
        // Get the user by ID
        $user = $this->userRepository->find($id);

        // If the user does not exist, return a 404 response
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        // Remove the user from the database
//        $entityManager = $this->getDoctrine()->getManager();
//        $entityManager->remove($user);
//        $entityManager->flush();

        // Return a JSON response
        return $this->json(['message' => 'User deleted']);
    }
}

<?php

namespace App\Controller;

use App\Service\UserService;
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
    public function create(Request $request, UserService $userService): Response {
        $data = json_decode($request->getContent(), true);

        if (!isset($data ['last_name'], $data ['first_name'],$data['username'], $data['email_address'], $data['password'])) {
            return $this->json(['error' => 'Missing required fields'], 400);
        }

        try {
            $user = $userService->createUser($data);
            return $this->json(['id' => $user->getId()]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
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
    public function update($id, Request $request, UserService $userService): Response {
        $data = json_decode($request->getContent(), true);

        $user = $this->userRepository->find($id);
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        try {
            $userService->updateUser($user, $data);
            return $this->json($user);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
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

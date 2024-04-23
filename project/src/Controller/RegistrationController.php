<?php

namespace App\Controller;

use App\Repository\RegistrationRepository;
use App\Repository\UserRepository;
use App\Repository\TournamentRepository;
use App\Service\RegistrationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    private RegistrationService $registrationService;
    private $registrationRepository;

    public function __construct(RegistrationService $registrationService) {
        $this->registrationService = $registrationService;
    }

    // Get the list of registrations for a tournament
    #[Route('/api/tournaments/{id}/registrations', name: 'show_registration', methods : ['GET'])]
    public function show($id): Response
    {
        $registrations = $this->registrationRepository->findBy(['tournament' => $id]);
        return $this->json($registrations);
    }

    // Register a player for a tournament
    #[Route('/api/tournaments/{id}/registrations', name: 'register_player', methods : ['POST'])]
    public function register(Request $request, int $id, EntityManagerInterface $entityManager, UserRepository $userRepository, TournamentRepository $tournamentRepository): Response
    {
        $userId = $request->request->get('user_id');
        $user = $userRepository->find($id);
        $tournament = $tournamentRepository->find($id);

        if (!$user || !$tournament) {
            return $this->json(['error' => 'User or tournament not found'], 404);
        }

        return $this->json(['message' => 'Registered successfully', 'userId' => $user->getId(), 'tournamentId' => $tournament->getId()]);
    }

    // Cancel a player's registration for a tournament
    #[Route('/api/tournaments/{idTournament}/registrations/{idRegistration}', name: 'cancel_registration', methods : ['DELETE'])]
    public function cancel(int $idTournament, int $idRegistration): Response {
        try {
            $user = $this->getUser();
            $this->registrationService->cancelRegistration($idRegistration, $user);
            return $this->json(['message' => 'Registration canceled']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}

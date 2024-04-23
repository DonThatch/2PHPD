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
    #[Route('/api/tournaments/{tournamentId}/registrations', name: 'register_player', methods: ['POST'])]
    public function register(Request $request, int $tournamentId): Response {
        $data = json_decode($request->getContent(), true);
        $userId = $data['user_id'] ?? null;

        if ($userId === null) {
            return $this->json(['error' => 'User ID must be provided'], 400);
        }

        try {
            $userId = (int) $userId;
            $registration = $this->registrationService->registerUserToTournament($userId, $tournamentId);
            return $this->json([
                'message' => 'Registered successfully',
                'userId' => $registration->getPlayer()->getId(),
                'tournamentId' => $registration->getTournament()->getId()
            ]);
        } catch (\Exception $e) {
            $status = $e->getCode() >= 100 && $e->getCode() < 600 ? $e->getCode() : 500;
            return $this->json(['error' => $e->getMessage()], $status);
        }
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

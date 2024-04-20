<?php

namespace App\Controller;

use App\Repository\RegistrationRepository;
use App\Repository\TournamentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    private $registrationRepository;
    private $tournamentRepository;
    private $entityManager;

    public function __construct(RegistrationRepository $registrationRepository, TournamentRepository $tournamentRepository, EntityManagerInterface $entityManager)
    {
        $this->registrationRepository = $registrationRepository;
        $this->tournamentRepository = $tournamentRepository;
        $this->entityManager = $entityManager;
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
    public function register($id): Response
    {
        $user = $this->getUser();

        $tournament = $this->tournamentRepository->find($id);

        if (!$tournament) {
            return $this->json(['error' => 'Tournament not found'], 404);
        }

        $existingRegistration = $this->registrationRepository->findOneBy(['tournament' => $tournament, 'user' => $user]);

        if ($existingRegistration) {
            return $this->json(['error' => 'User already registered for the tournament'], 400);
        }

        $registration = new Registration();
        $registration->setTournament($tournament);
        $registration->setUser($user);

        $this->entityManager->persist($registration);
        $this->entityManager->flush();

        return $this->json($registration);
    }

    // Cancel a player's registration for a tournament
    #[Route('/api/tournaments/{idTournament}/registrations/{idRegistration}', name: 'cancel_registration', methods : ['DELETE'])]
    public function cancel($idTournament, $idRegistration): Response
    {
        $user = $this->getUser();

        $tournament = $this->tournamentRepository->find($idTournament);

        if (!$tournament) {
            return $this->json(['error' => 'Tournament not found'], 404);
        }

        $registration = $this->registrationRepository->find($idRegistration);

        if (!$registration) {
            return $this->json(['error' => 'Registration not found'], 404);
        }

        if ($registration->getUser() !== $user) {
            return $this->json(['error' => 'You are not authorized to cancel this registration'], 403);
        }

        $this->entityManager->remove($registration);
        $this->entityManager->flush();

        return $this->json(['message' => 'Registration canceled']);
    }
}

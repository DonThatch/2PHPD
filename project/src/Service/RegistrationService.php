<?php
// src/Service/RegistrationService.php

namespace App\Service;

use App\Entity\Registration;
use App\Entity\Tournament;
use App\Entity\User;
use App\Repository\RegistrationRepository;
use App\Repository\TournamentRepository;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationService {
    private EntityManagerInterface $entityManager;
    private RegistrationRepository $registrationRepository;
    private TournamentRepository $tournamentRepository;

    public function __construct(EntityManagerInterface $entityManager, RegistrationRepository $registrationRepository, TournamentRepository $tournamentRepository) {
        $this->entityManager = $entityManager;
        $this->registrationRepository = $registrationRepository;
        $this->tournamentRepository = $tournamentRepository;
    }

    public function registerUserToTournament(int $userId, int $tournamentId): Registration
    {
        $user = $this->entityManager->getRepository(User::class)->find($userId);
        $tournament = $this->entityManager->getRepository(Tournament::class)->find($tournamentId);

        if (!$user || !$tournament) {
            throw new \Exception('User or tournament not found');
        }

        $existingRegistration = $this->registrationRepository->findOneBy([
            'tournament' => $tournament,
            'user' => $user
        ]);
        if ($existingRegistration) {
            throw new \Exception('User already registered for this tournament');
        }

        $registration = new Registration();
        $registration->setTournament($tournament);
        $registration->setPlayer($user);
        $registration->setRegistrationDate(new \DateTime()); // Explicitly set the current time as the registration date

        $this->entityManager->persist($registration);
        $this->entityManager->flush();

        return $registration;
    }

    public function cancelRegistration(int $registrationId, User $user): void {
        $registration = $this->registrationRepository->find($registrationId);
        if (!$registration) {
            throw new \Exception('Registration not found');
        }

        if ($registration->getUser() !== $user) {
            throw new \Exception('You are not authorized to cancel this registration');
        }

        $this->entityManager->remove($registration);
        $this->entityManager->flush();
    }
}

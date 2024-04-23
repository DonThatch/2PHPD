<?php
// src/Service/TournamentService.php

namespace App\Service;

use App\Entity\User;
use App\Entity\Tournament;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class TournamentService {
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function createTournament(array $data) {
        if (!isset($data['tournament_name'], $data['start_date'], $data['end_date'], $data['description'], $data['max_participants'], $data['organizer_id'])) {
            throw new \Exception('Missing required fields');
        }

        $organizer = $this->entityManager->getRepository(User::class)->find($data['organizer_id']);
        if (!$organizer) {
            throw new \Exception("Organizer not found");
        }

        $tournament = new Tournament();
        $tournament->setOrganizer($organizer);
        $tournament->setTournamentName($data['tournament_name']);
        $tournament->setStartDate(new DateTime($data['start_date']));
        $tournament->setEndDate(new DateTime($data['end_date']));
        $tournament->setDescription($data['description']);
        $tournament->setLocation($data['location'] ?? 'Default Location');
        $tournament->setMaxParticipants($data['max_participants'] ?? 0);
        $tournament->setStatus($data['status'] ?? false);
        $tournament->setSport($data['sport'] ?? 'Undefined');

        $this->entityManager->persist($tournament);
        $this->entityManager->flush();

        return $tournament;
    }

    public function getAllTournaments() {
        return $this->entityManager->createQueryBuilder()
            ->select('tournament', 'organizer', 'winner')
            ->addSelect('organizer', 'winner')
            ->from('App\Entity\Tournament', 'tournament')
            ->leftJoin('tournament.organizer', 'organizer')
            ->leftJoin('tournament.winner', 'winner')
            ->getQuery()
            ->getResult();
    }

}

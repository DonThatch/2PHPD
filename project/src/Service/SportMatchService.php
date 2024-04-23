<?php

namespace App\Service;

use App\Entity\SportMatch;
use App\Entity\Tournament;
use App\Repository\UserRepository;
use App\Repository\SportMatchRepository;
use App\Repository\TournamentRepository;
use Doctrine\ORM\EntityManagerInterface;

class SportMatchService {
    private EntityManagerInterface $entityManager;
    private SportMatchRepository $sportMatchRepository;
    private TournamentRepository $tournamentRepository;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $entityManager, SportMatchRepository $sportMatchRepository, TournamentRepository $tournamentRepository, UserRepository $userRepository) {
        $this->entityManager = $entityManager;
        $this->sportMatchRepository = $sportMatchRepository;
        $this->tournamentRepository = $tournamentRepository;
        $this->userRepository = $userRepository;
    }

    public function listSportMatches(int $tournamentId) {
        return $this->sportMatchRepository->findBy(['tournament' => $tournamentId]);
    }

    public function createSportMatch(int $tournamentId, array $matchData): SportMatch {
        $tournament = $this->tournamentRepository->find($tournamentId);
        if (!$tournament) {
            throw new \Exception('No tournament found for id ' . $tournamentId);
        }

        $sportMatch = new SportMatch();
        $sportMatch->setTournament($tournament);

        if (isset($matchData['player1Id'])) {
            $player1 = $this->userRepository->find($matchData['player1Id']);
            $sportMatch->setPlayer1($player1);
        }

        if (isset($matchData['player2Id'])) {
            $player2 = $this->userRepository->find($matchData['player2Id']);
            $sportMatch->setPlayer2($player2);
        }

        if (isset($matchData['status'])) {
            $sportMatch->setStatus($matchData['status']);
        }

        $this->entityManager->persist($sportMatch);
        $this->entityManager->flush();

        return $sportMatch;
    }

    public function getSportMatch(int $tournamentId, int $sportMatchId): ?SportMatch {
        return $this->sportMatchRepository->findOneBy(['tournament' => $tournamentId, 'id' => $sportMatchId]);
    }

    public function updateSportMatch(int $tournamentId, int $sportMatchId, array $data): SportMatch {
        $sportMatch = $this->getSportMatch($tournamentId, $sportMatchId);
        if (!$sportMatch) {
            throw new \Exception('Sport match not found');
        }

        $sportMatch->setResult($data['result']);
        $this->entityManager->flush();

        return $sportMatch;
    }

    public function deleteSportMatch(int $tournamentId, int $sportMatchId): void {
        $sportMatch = $this->getSportMatch($tournamentId, $sportMatchId);
        if (!$sportMatch) {
            throw new \Exception('Sport match not found');
        }

        $this->entityManager->remove($sportMatch);
        $this->entityManager->flush();
    }
}

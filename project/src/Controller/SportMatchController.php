<?php

namespace App\Controller;

use App\Entity\SportMatch;
use App\Repository\SportMatchRepository;
use App\Repository\TournamentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\SportMatchService;

class SportMatchController extends AbstractController
{
    private SportMatchService $sportMatchService;

    public function __construct(SportMatchService $sportMatchService) {
        if (!$sportMatchService) {
            throw new \LogicException('SportMatchService not injected correctly!');
        }
        $this->sportMatchService = $sportMatchService;
    }

    // Get the list of sport matches for a tournament
    #[Route('/api/tournaments/{id}/sport-matchs', name: 'sport_match_list', methods: ['GET'])]
    public function list($id): Response {
        $sportMatches = $this->sportMatchService->listSportMatches($id);
        return $this->json($sportMatches);
    }

    // Create a new sport match for a tournament
    #[Route('/api/tournaments/{id}/sport-matchs', name: 'sport_match_create', methods: ['POST'])]
    public function create($id, Request $request): Response {
        try {
            $matchData = json_decode($request->getContent(), true);
            if (!is_array($matchData)) {
                return $this->json(['error' => 'Invalid input data'], Response::HTTP_BAD_REQUEST);
            }

            $sportMatch = $this->sportMatchService->createSportMatch($id, $matchData);
            return $this->json($sportMatch);
        } catch (\Exception $e) {
            // Handle exceptions, such as no tournament found or database errors
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Get details of a sport match
    #[Route('/api/tournaments/{idTournament}/sport-matchs/{idSportMatchs}', name: 'sport_match_show', methods: ['GET'])]
    public function show($idTournament, $idSportMatchs): Response
    {
        $sportMatch = $this->sportMatchRepository->findOneBy(['tournament' => $idTournament, 'id' => $idSportMatchs]);

        if (!$sportMatch) {
            return $this->json(['error' => 'Sport match not found'], 404);
        }

        return $this->json($sportMatch);
    }

    // Update the result of a sport match
    #[Route('/api/tournaments/{idTournament}/sport-matchs/{idSportMatchs}', name: 'sport_match_update', methods: ['PUT'])]
    public function update($idTournament, $idSportMatchs, Request $request, EntityManagerInterface $entityManager): Response
    {
        $sportMatch = $this->sportMatchRepository->findOneBy(['tournament' => $idTournament, 'id' => $idSportMatchs]);

        if (!$sportMatch) {
            return $this->json(['error' => 'Sport match not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $sportMatch->setResult($data['result']);

        $entityManager->flush();

        return $this->json($sportMatch);
    }

    // Delete a sport match
    #[Route('/api/tournaments/{idTournament}/sport-matchs/{idSportMatchs}', name: 'sport_match_delete', methods: ['DELETE'])]
    public function delete($idTournament, $idSportMatchs, EntityManagerInterface $entityManager): Response
    {
        $sportMatch = $this->sportMatchRepository->findOneBy(['tournament' => $idTournament, 'id' => $idSportMatchs]);

        if (!$sportMatch) {
            return $this->json(['error' => 'Sport match not found'], 404);
        }

        $entityManager->remove($sportMatch);
        $entityManager->flush();

        return new Response(null, 204);
    }

}

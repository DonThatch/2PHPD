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

class SportMatchController extends AbstractController
{
    private $sportMatchRepository;

    public function __construct(SportMatchRepository $sportMatchRepository)
    {
        $this->sportMatchRepository = $sportMatchRepository;
    }

    // Get the list of sport matches for a tournament
    #[Route('/api/tournaments/{id}/sport-matchs', name: 'sport_match_list', methods: ['GET'])]
    public function list($id): Response
    {
        // Get all sport matches for the tournament
        $sportMatches = $this->sportMatchRepository->findBy(['tournament' => $id]);

        // Return a JSON response
        return $this->json($sportMatches);
    }

    // Create a new sport match for a tournament
    #[Route('/api/tournaments/{id}/sport-matchs', name: 'sport_match_create', methods: ['POST'])]
    public function create($id, EntityManagerInterface $entityManager, TournamentRepository $tournamentRepository): Response
    {
        // Fetch the Tournament entity from the database
        $tournament = $tournamentRepository->find($id);

        if (!$tournament) {
            throw $this->createNotFoundException('No tournament found for id '.$id);
        }

        // Create a new sport match
        $sportMatch = new SportMatch();
        $sportMatch->setTournament($tournament);

        // Save the sport match to the database
        $entityManager->persist($sportMatch);
        $entityManager->flush();

        // Return a JSON response
        return $this->json($sportMatch);
    }

    // Get details of a sport match
    #[Route('/api/tournaments/{idTournament}/sport-matchs/{idSportMatchs}', name: 'sport_match_show', methods: ['GET'])]
    public function show($idTournament, $idSportMatchs): Response
    {
        // Get the sport match by ID
        $sportMatch = $this->sportMatchRepository->findOneBy(['tournament' => $idTournament, 'id' => $idSportMatchs]);

        // If the sport match does not exist, return a 404 response
        if (!$sportMatch) {
            return $this->json(['error' => 'Sport match not found'], 404);
        }

        // Return a JSON response
        return $this->json($sportMatch);
    }

    // Update the result of a sport match
    #[Route('/api/tournaments/{idTournament}/sport-matchs/{idSportMatchs}', name: 'sport_match_update', methods: ['PUT'])]
    public function update($idTournament, $idSportMatchs, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get the sport match by ID
        $sportMatch = $this->sportMatchRepository->findOneBy(['tournament' => $idTournament, 'id' => $idSportMatchs]);

        // If the sport match does not exist, return a 404 response
        if (!$sportMatch) {
            return $this->json(['error' => 'Sport match not found'], 404);
        }

        // Decode the JSON request body into an array
        $data = json_decode($request->getContent(), true);

        // Update the result of the sport match
        $sportMatch->setResult($data['result']);

        // Save the updated sport match to the database
        $entityManager->flush();

        // Return a JSON response
        return $this->json($sportMatch);
    }

    // Delete a sport match
    #[Route('/api/tournaments/{idTournament}/sport-matchs/{idSportMatchs}', name: 'sport_match_delete', methods: ['DELETE'])]
    public function delete($idTournament, $idSportMatchs, EntityManagerInterface $entityManager): Response
    {
        // Get the sport match by ID
        $sportMatch = $this->sportMatchRepository->findOneBy(['tournament' => $idTournament, 'id' => $idSportMatchs]);

        // If the sport match does not exist, return a 404 response
        if (!$sportMatch) {
            return $this->json(['error' => 'Sport match not found'], 404);
        }

        // Remove the sport match from the database
        $entityManager->remove($sportMatch);
        $entityManager->flush();

        // Return a 204 (No Content) response
        return new Response(null, 204);
    }

}

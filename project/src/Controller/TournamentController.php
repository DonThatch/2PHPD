<?php

namespace App\Controller;

use App\Repository\TournamentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tournament;
use DateTime;

class TournamentController extends AbstractController
{
    private $tournamentRepository;

    public function __construct(TournamentRepository $tournamentRepository)
    {
        $this->tournamentRepository = $tournamentRepository;
    }

    // Get the list of tournaments
    #[Route('/api/tournaments', name: 'tournament_list', methods: ['GET'])]
    public function list(): Response
    {
        // Get all tournaments
        $tournaments = $this->tournamentRepository->findAll();

        // Return a JSON response
        return $this->json($tournaments);
    }

    // Get details of a tournament
    #[Route('/api/tournaments/{id}', name: 'tournament_show', methods: ['GET'])]
    public function show($id): Response
    {
        // Get the tournament by ID
        $tournament = $this->tournamentRepository->find($id);

        // If the tournament does not exist, return a 404 response
        if (!$tournament) {
            return $this->json(['error' => 'Tournament not found'], 404);
        }

        // Return a JSON response
        return $this->json($tournament);
    }

    // Create a new tournament
    #[Route('/api/tournaments', name: 'tournament_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        // Decode the JSON request body into an array
        $data = json_decode($request->getContent(), true);

        // Check for the required fields in the JSON data
        if (!isset($data['tournamentName'], $data['startDate'], $data['endDate'], $data['description'])) {
            return $this->json(['error' => 'Missing required fields'], 400);
        }

        // Create a new tournament object
        $tournament = new Tournament();
        $tournament->setTournamentName($data['tournamentName']);
        $tournament->setStartDate(new DateTime($data['startDate']));
        $tournament->setEndDate(new DateTime($data['endDate']));
        $tournament->setDescription($data['description']);

        // Optional fields
        $tournament->setLocation($data['location'] ?? 'Default Location');
        $tournament->setMaxParticipants($data['maxParticipants'] ?? 0);
        $tournament->setStatus($data['status'] ?? false);
        $tournament->setSport($data['sport'] ?? 'Undefined');

        // Persist the tournament object
        // $em = $this->getDoctrine()->getManager();
        // $em->persist($tournament);
        // $em->flush();

        // Return a JSON response
        return $this->json($tournament);
    }

    // Update an existing tournament
    #[Route('/api/tournaments/{id}', name: 'tournament_update', methods: ['PUT'])]
    public function update($id, Request $request): Response
    {
        // Get the tournament by ID
        $tournament = $this->tournamentRepository->find($id);

        // If the tournament does not exist, return a 404 response
        if (!$tournament) {
            return $this->json(['error' => 'Tournament not found'], 404);
        }

        // Decode the JSON request body into an array
        $data = json_decode($request->getContent(), true);

        // Update the tournament object with the new data
        if (isset($data['tournamentName'])) {
            $tournament->setTournamentName($data['tournamentName']);
        }
        if (isset($data['startDate'])) {
            $tournament->setStartDate(new DateTime($data['startDate']));
        }
        if (isset($data['endDate'])) {
            $tournament->setEndDate(new DateTime($data['endDate']));
        }
        if (isset($data['description'])) {
            $tournament->setDescription($data['description']);
        }
        if (isset($data['location'])) {
            $tournament->setLocation($data['location']);
        }
        if (isset($data['maxParticipants'])) {
            $tournament->setMaxParticipants($data['maxParticipants']);
        }
        if (isset($data['status'])) {
            $tournament->setStatus($data['status']);
        }
        if (isset($data['sport'])) {
            $tournament->setSport($data['sport']);
        }

        // Persist the updated tournament object
        // $em = $this->getDoctrine()->getManager();
        // $em->flush();

        // Return a JSON response
        return $this->json($tournament);
    }

    // Delete a tournament
    #[Route('/api/tournaments/{id}', name: 'tournament_delete', methods: ['DELETE'])]
    public function delete($id): Response
    {
        // Get the tournament by ID
        $tournament = $this->tournamentRepository->find($id);

        // If the tournament does not exist, return a 404 response
        if (!$tournament) {
            return $this->json(['error' => 'Tournament not found'], 404);
        }

        // Remove the tournament object
        //  $em = $this->getDoctrine()->getManager();
        //  $em->remove($tournament);
        //  $em->flush();

        // Return a JSON response
        return $this->json(['message' => 'Tournament deleted']);
    }

}

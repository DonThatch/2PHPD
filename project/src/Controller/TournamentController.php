<?php

namespace App\Controller;

use App\Service\TournamentService;
use App\Repository\TournamentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Tournament;
use DateTime;

class TournamentController extends AbstractController
{
    private $tournamentRepository;

<<<<<<< HEAD
    private $tournamentService;

    private $serializer;

    public function __construct(TournamentRepository $tournamentRepository, TournamentService $tournamentService, SerializerInterface $serializer) {
        $this->tournamentRepository = $tournamentRepository;
        $this->tournamentService = $tournamentService;
        $this->serializer = $serializer;
=======
    public function __construct(TournamentRepository $tournamentRepository)
    {
        $this->tournamentRepository = $tournamentRepository;
>>>>>>> parent of 8f33b8e (inscription front + maj controller)
    }

    // Get the list of tournaments
    #[Route('/api/tournaments', name: 'tournament_list', methods: ['GET'])]
<<<<<<< HEAD
    public function list(): Response {
        $tournaments = $this->tournamentService->getAllTournaments();
        $json = $this->serializer->serialize($tournaments, 'json', ['groups' => 'tournament']);
        return new Response($json, 200, ['Content-Type' => 'application/json']);
=======
    public function list(): Response
    {
        // Get all tournaments
        $tournaments = $this->tournamentRepository->findAll();

        // Return a JSON response
        return $this->json($tournaments);
>>>>>>> parent of 8f33b8e (inscription front + maj controller)
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
    public function create(Request $request, TournamentService $tournamentService): Response {
        $data = json_decode($request->getContent(), true);

        if ($data === null || !isset($data['tournament_name'], $data['start_date'], $data['end_date'], $data['description'], $data ['max_participants'], $data['organizer_id'])) {
            return $this->json(['error' => 'Invalid or missing JSON data'], 400);
        }

        try {
            $tournament = $tournamentService->createTournament($data);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        return $this->json([
            'success' => true,
            'id' => $tournament->getId()
        ]);
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
        if (isset($data['tournament_name'])) {
            $tournament->setTournamentName($data['tournament_name']);
        }
        if (isset($data['startDate'])) {
            $tournament->setStartDate(new DateTime($data['start_date']));
        }
        if (isset($data['endDate'])) {
            $tournament->setEndDate(new DateTime($data['end_date']));
        }
        if (isset($data['description'])) {
            $tournament->setDescription($data['description']);
        }
        if (isset($data['location'])) {
            $tournament->setLocation($data['location']);
        }
        if (isset($data['maxParticipants'])) {
            $tournament->setMaxParticipants($data['max_participants']);
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

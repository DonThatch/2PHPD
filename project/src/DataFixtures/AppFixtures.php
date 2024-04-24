<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Tournament;
use App\Entity\User;
use DateTime;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Create a new Organizer object
        $organizer = new Organizer();
        $organizer->setName('Test Organizer');
        // Set other properties of the organizer as needed

        // Persist the organizer object
        $manager->persist($organizer);

        // Create a new Tournament object
        $tournament = new Tournament();

        // Set properties
        $tournament->setTournamentName('Test Tournament');
        $tournament->setStartDate(new DateTime('2022-01-01'));
        $tournament->setEndDate(new DateTime('2022-01-31'));
        $tournament->setLocation('Test Location');
        $tournament->setDescription('Test Description');
        $tournament->setMaxParticipants(10);
        $tournament->setStatus(true);
        $tournament->setSport('Test Sport');

        // Set the organizer on the tournament
        $tournament->setOrganizer($organizer);

        // Persist the tournament object
        $manager->persist($tournament);

        // Flush the manager to write changes to the database
        $manager->flush();
    }
}
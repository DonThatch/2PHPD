<?php

namespace App\Entity;

use App\Repository\SportMatchRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SportMatchRepository::class)]
class SportMatch
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'relSportMatchTournament')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tournament $tournament = null;

    #[ORM\ManyToOne(inversedBy: 'relSportMatchPlayer1')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $player1 = null;

    #[ORM\ManyToOne(inversedBy: 'relSportMatchPlayer2')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $player2 = null;

    #[ORM\Column(length: 255)]
    private ?string $status = 'En attente';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): static
    {
        $this->tournament = $tournament;

        return $this;
    }

    public function getPlayer1(): ?User
    {
        return $this->player1;
    }

    public function setPlayer1(?User $player1): static
    {
        $this->player1 = $player1;

        return $this;
    }

    public function getPlayer2(): ?User
    {
        return $this->player2;
    }

    public function setPlayer2(?User $player2): static
    {
        $this->player2 = $player2;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}

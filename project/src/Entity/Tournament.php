<?php

namespace App\Entity;

use App\Repository\TournamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TournamentRepository::class)]
class Tournament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $tournamentName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $maxParticipants = null;

    #[ORM\Column]
    private ?bool $status = null;

    #[ORM\Column(length: 255)]
    private ?string $sport = null;

    /**
     * @var Collection<int, Registration>
     */
    #[ORM\OneToMany(targetEntity: Registration::class, mappedBy: 'tournament')]
    private Collection $relRegistrationTournament;

    /**
     * @var Collection<int, SportMatch>
     */
    #[ORM\OneToMany(targetEntity: SportMatch::class, mappedBy: 'tournament')]
    private Collection $relSportMatchTournament;

    #[ORM\ManyToOne(inversedBy: 'relOrgaTournamentUser')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $organizer = null;

    #[ORM\ManyToOne(inversedBy: 'relWinTournamentUser')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $winner = null;

    public function __construct()
    {
        $this->relRegistrationTournament = new ArrayCollection();
        $this->relSportMatchTournament = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTournamentName(): ?string
    {
        return $this->tournamentName;
    }

    public function setTournamentName(string $tournamentName): static
    {
        $this->tournamentName = $tournamentName;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getMaxParticipants(): ?int
    {
        return $this->maxParticipants;
    }

    public function setMaxParticipants(int $maxParticipants): static
    {
        $this->maxParticipants = $maxParticipants;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getSport(): ?string
    {
        return $this->sport;
    }

    public function setSport(string $sport): static
    {
        $this->sport = $sport;

        return $this;
    }

    /**
     * @return Collection<int, Registration>
     */
    public function getRelRegistrationTournament(): Collection
    {
        return $this->relRegistrationTournament;
    }

    public function addRelRegistrationTournament(Registration $relRegistrationTournament): static
    {
        if (!$this->relRegistrationTournament->contains($relRegistrationTournament)) {
            $this->relRegistrationTournament->add($relRegistrationTournament);
            $relRegistrationTournament->setTournament($this);
        }

        return $this;
    }

    public function removeRelRegistrationTournament(Registration $relRegistrationTournament): static
    {
        if ($this->relRegistrationTournament->removeElement($relRegistrationTournament)) {
            // set the owning side to null (unless already changed)
            if ($relRegistrationTournament->getTournament() === $this) {
                $relRegistrationTournament->setTournament(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SportMatch>
     */
    public function getRelSportMatchTournament(): Collection
    {
        return $this->relSportMatchTournament;
    }

    public function addRelSportMatchTournament(SportMatch $relSportMatchTournament): static
    {
        if (!$this->relSportMatchTournament->contains($relSportMatchTournament)) {
            $this->relSportMatchTournament->add($relSportMatchTournament);
            $relSportMatchTournament->setTournament($this);
        }

        return $this;
    }

    public function removeRelSportMatchTournament(SportMatch $relSportMatchTournament): static
    {
        if ($this->relSportMatchTournament->removeElement($relSportMatchTournament)) {
            // set the owning side to null (unless already changed)
            if ($relSportMatchTournament->getTournament() === $this) {
                $relSportMatchTournament->setTournament(null);
            }
        }

        return $this;
    }

    public function getOrganizer(): ?User
    {
        return $this->organizer;
    }

    public function setOrganizer(?User $organizer): static
    {
        $this->organizer = $organizer;

        return $this;
    }

    public function getWinner(): ?User
    {
        return $this->winner;
    }

    public function setWinner(?User $winner): static
    {
        $this->winner = $winner;

        return $this;
    }
}

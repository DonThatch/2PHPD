<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $emailAddress = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $status = 'actif';

    /**
     * @var Collection<int, Registration>
     */
    #[ORM\OneToMany(targetEntity: Registration::class, mappedBy: 'user')]
    private Collection $relRegistrationUser;

    /**
     * @var Collection<int, SportMatch>
     */
    #[ORM\OneToMany(targetEntity: SportMatch::class, mappedBy: 'player1')]
    private Collection $relSportMatchPlayer1;

    /**
     * @var Collection<int, SportMatch>
     */
    #[ORM\OneToMany(targetEntity: SportMatch::class, mappedBy: 'player2')]
    private Collection $relSportMatchPlayer2;

    /**
     * @var Collection<int, Tournament>
     */
    #[ORM\OneToMany(targetEntity: Tournament::class, mappedBy: 'organizer')]
    private Collection $relOrgaTournamentUser;

    /**
     * @var Collection<int, Tournament>
     */
    #[ORM\OneToMany(targetEntity: Tournament::class, mappedBy: 'winner')]
    private Collection $relWinTournamentUser;

    public function __construct()
    {
        $this->relRegistrationUser = new ArrayCollection();
        $this->relSportMatchPlayer1 = new ArrayCollection();
        $this->relSportMatchPlayer2 = new ArrayCollection();
        $this->relOrgaTournamentUser = new ArrayCollection();
        $this->relWinTournamentUser = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(string $emailAddress): static
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

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

    /**
     * @return Collection<int, Registration>
     */
    public function getRelRegistrationUser(): Collection
    {
        return $this->relRegistrationUser;
    }

    public function addRelRegistrationUser(Registration $relRegistrationUser): static
    {
        if (!$this->relRegistrationUser->contains($relRegistrationUser)) {
            $this->relRegistrationUser->add($relRegistrationUser);
            $relRegistrationUser->setPlayer($this);
        }

        return $this;
    }

    public function removeRelRegistrationUser(Registration $relRegistrationUser): static
    {
        if ($this->relRegistrationUser->removeElement($relRegistrationUser)) {
            if ($relRegistrationUser->getPlayer() === $this) {
                $relRegistrationUser->setPlayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SportMatch>
     */
    public function getRelSportMatchPlayer1(): Collection
    {
        return $this->relSportMatchPlayer1;
    }

    public function addRelSportMatchPlayer1(SportMatch $relSportMatchPlayer1): static
    {
        if (!$this->relSportMatchPlayer1->contains($relSportMatchPlayer1)) {
            $this->relSportMatchPlayer1->add($relSportMatchPlayer1);
            $relSportMatchPlayer1->setPlayer1($this);
        }

        return $this;
    }

    public function removeRelSportMatchPlayer1(SportMatch $relSportMatchPlayer1): static
    {
        if ($this->relSportMatchPlayer1->removeElement($relSportMatchPlayer1)) {
            if ($relSportMatchPlayer1->getPlayer1() === $this) {
                $relSportMatchPlayer1->setPlayer1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SportMatch>
     */
    public function getRelSportMatchPlayer2(): Collection
    {
        return $this->relSportMatchPlayer2;
    }

    public function addRelSportMatchPlayer2(SportMatch $relSportMatchPlayer2): static
    {
        if (!$this->relSportMatchPlayer2->contains($relSportMatchPlayer2)) {
            $this->relSportMatchPlayer2->add($relSportMatchPlayer2);
            $relSportMatchPlayer2->setPlayer2($this);
        }

        return $this;
    }

    public function removeRelSportMatchPlayer2(SportMatch $relSportMatchPlayer2): static
    {
        if ($this->relSportMatchPlayer2->removeElement($relSportMatchPlayer2)) {
            // set the owning side to null (unless already changed)
            if ($relSportMatchPlayer2->getPlayer2() === $this) {
                $relSportMatchPlayer2->setPlayer2(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tournament>
     */
    public function getRelOrgaTournamentUser(): Collection
    {
        return $this->relOrgaTournamentUser;
    }

    public function addRelOrgaTournamentUser(Tournament $relOrgaTournamentUser): static
    {
        if (!$this->relOrgaTournamentUser->contains($relOrgaTournamentUser)) {
            $this->relOrgaTournamentUser->add($relOrgaTournamentUser);
            $relOrgaTournamentUser->setOrganizer($this);
        }

        return $this;
    }

    public function removeRelOrgaTournamentUser(Tournament $relOrgaTournamentUser): static
    {
        if ($this->relOrgaTournamentUser->removeElement($relOrgaTournamentUser)) {
            // set the owning side to null (unless already changed)
            if ($relOrgaTournamentUser->getOrganizer() === $this) {
                $relOrgaTournamentUser->setOrganizer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tournament>
     */
    public function getRelWinTournamentUser(): Collection
    {
        return $this->relWinTournamentUser;
    }

    public function addRelWinTournamentUser(Tournament $relWinTournamentUser): static
    {
        if (!$this->relWinTournamentUser->contains($relWinTournamentUser)) {
            $this->relWinTournamentUser->add($relWinTournamentUser);
            $relWinTournamentUser->setWinner($this);
        }

        return $this;
    }

    public function removeRelWinTournamentUser(Tournament $relWinTournamentUser): static
    {
        if ($this->relWinTournamentUser->removeElement($relWinTournamentUser)) {
            // set the owning side to null (unless already changed)
            if ($relWinTournamentUser->getWinner() === $this) {
                $relWinTournamentUser->setWinner(null);
            }
        }

        return $this;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getRegistrations(): array
    {
        $registrations = [];
        foreach ($this->relRegistrationUser as $registration) {
            $registrations[] = [
                'id' => $registration->getId(),
                'tournament' => $registration->getTournament()->getId(),
                'registrationDate' => $registration->getRegistrationDate(),
                'status' => $registration->getStatus(),
            ];
        }
        return $registrations;
    }
}

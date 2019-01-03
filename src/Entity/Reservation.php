<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $demiJournee;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $token;

    /**
     * @ORM\Column(type="integer")
     */
    private $prixTotal;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Billet", mappedBy="reservation", cascade={"persist", "remove"}))
     */
    private $billets;

    public function __construct()
    {
        $this->billets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDemiJournee(): ?bool
    {
        return $this->demiJournee;
    }

    public function setDemiJournee(bool $demiJournee): self
    {
        $this->demiJournee = $demiJournee;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }


    // Converti le string en datetime

    public function setDate(string $date): self
    {
        date_default_timezone_set('Europe/Paris');
        $myDateTime = \DateTime::createFromFormat('d-m-Y', $date);

        $today = date('d-m-Y');
        $heure = date('H');

        if (date($date) === $today AND $heure > 14) { $this->setDemiJournee(true); }

        $this->date = $myDateTime;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getPrixTotal(): ?int
    {
        return $this->prixTotal;
    }

    // Boucle sur le prix des billets pour calculer le prix total

    public function setPrixTotal(): self

    {
        $this->prixTotal = 0;
        $billets = $this->getBillets()->toArray();
        foreach ($billets as $billet) {
            $this->prixTotal += $billet->getPrix();
        }
        if ($this->demiJournee === true) {
            $this->prixTotal = $this->prixTotal / 2;
        }
        return $this;
    }

    /**
     * @return Collection|Billet[]
     */
    public function getBillets(): Collection
    {
        return $this->billets;
    }

    public function addBillet(Billet $billet): self
    {
        if (!$this->billets->contains($billet)) {
            $this->billets[] = $billet;
            $billet->setReservation($this);
        }

        return $this;
    }

    public function removeBillet(Billet $billet): self
    {
        if ($this->billets->contains($billet)) {
            $this->billets->removeElement($billet);
            // set the owning side to null (unless already changed)
            if ($billet->getReservation() === $this) {
                $billet->setReservation(null);
            }
        }

        return $this;
    }


}

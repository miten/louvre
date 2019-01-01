<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BilletRepository")
 */
class Billet
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */


    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\reservation", inversedBy="billets")
     */
    private $reservation;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\Column(type="boolean")
     */
    private $tarifReduit;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $tarif;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="integer")
     */
    private $age;






    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReservation(): ?reservation
    {
        return $this->reservation;
    }

    public function setReservation(?reservation $reservation): self
    {
        $this->reservation = $reservation;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }


    public function getTarifReduit(): ?bool
    {
        return $this->tarifReduit;
    }

    public function setTarifReduit(string $tarifReduit): self
    {
        $this->tarifReduit = $tarifReduit;
        $this->setPrix(13);
        return $this;
    }


    public function getTarif(): ?string
    {
        return $this->tarif;
    }

    public function setTarif(string $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }


    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(\DateTimeInterface $age): self
    {

        $now = new \DateTime();
        $interval = $now->diff($age);
        $this->age = $interval->y;

        return $this;
    }



    public function setCode(): self
    {

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $code = '';
        for ($i = 0; $i < 10; $i++) {
            $code .= $characters[rand(0, $charactersLength - 1)];
        }

        $this->code = $code;

        return $this;
    }

}

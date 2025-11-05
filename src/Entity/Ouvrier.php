<?php

namespace App\Entity;

use App\Repository\OuvrierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OuvrierRepository::class)]
class Ouvrier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $nom = null;

    #[ORM\Column(length: 30)]
    private ?string $prenom = null;

    #[ORM\Column(nullable: true)]
    private ?float $salaire = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $daten = null;

    #[ORM\Column(nullable: true)]
    private ?int $grades = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getSalaire(): ?float
    {
        return $this->salaire;
    }

    public function setSalaire(?float $salaire): static
    {
        $this->salaire = $salaire;

        return $this;
    }

    public function getDaten(): ?\DateTime
    {
        return $this->daten;
    }

    public function setDaten(?\DateTime $daten): static
    {
        $this->daten = $daten;

        return $this;
    }

    public function getGrades(): ?int
    {
        return $this->grades;
    }

    public function setGrades(?int $grades): static
    {
        $this->grades = $grades;

        return $this;
    }
}

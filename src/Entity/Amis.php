<?php

namespace App\Entity;

use App\Repository\AmisRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AmisRepository::class)]
class Amis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $demandeur = null;

    #[ORM\Column]
    private ?int $cible = null;

    #[ORM\Column(length: 255)]
    private ?string $demande = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDemandeur(): ?int
    {
        return $this->demandeur;
    }

    public function setDemandeur(int $demandeur): static
    {
        $this->demandeur = $demandeur;

        return $this;
    }

    public function getCible(): ?int
    {
        return $this->cible;
    }

    public function setCible(int $cible): static
    {
        $this->cible = $cible;

        return $this;
    }

    public function getDemande(): ?string
    {
        return $this->demande;
    }

    public function setDemande(string $demande): static
    {
        $this->demande = $demande;

        return $this;
    }
}

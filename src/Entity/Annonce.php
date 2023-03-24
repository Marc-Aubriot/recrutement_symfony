<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: AnnonceRepository::class)]
class Annonce
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private $id;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    #[ORM\Column(length: 100)]
    private ?string $recruteurEmail = null;

    public function getRecruteurEmail(): ?string
    {
        return $this->recruteurEmail;
    }

    public function setRecruteurEmail(string $recruteurEmail): self
    {
        $this->recruteurEmail = $recruteurEmail;

        return $this;
    }

    #[ORM\Column(length: 255)]
    private ?string $intitulé = null;

    #[ORM\Column(length: 255)]
    private ?string $nomEntreprise = null;

    #[ORM\Column(length: 255)]
    private ?string $adresseEntreprise = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCréation = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $horaires = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $salaire = null;

    #[ORM\Column]
    private ?bool $validation_statut = null;
    
    public function getIntitulé(): ?string
    {
        return $this->intitulé;
    }

    public function setIntitulé(string $intitulé): self
    {
        $this->intitulé = $intitulé;

        return $this;
    }

    public function getNomEntreprise(): ?string
    {
        return $this->nomEntreprise;
    }

    public function setNomEntreprise(string $nomEntreprise): self
    {
        $this->nomEntreprise = $nomEntreprise;

        return $this;
    }

    public function getAdresseEntreprise(): ?string
    {
        return $this->adresseEntreprise;
    }

    public function setAdresseEntreprise(string $adresseEntreprise): self
    {
        $this->adresseEntreprise = $adresseEntreprise;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateCréation(): ?\DateTimeInterface
    {
        return $this->dateCréation;
    }

    public function setDateCréation(\DateTimeInterface $dateCréation): self
    {
        $this->dateCréation = $dateCréation;

        return $this;
    }

    public function getHoraires(): ?string
    {
        return $this->horaires;
    }

    public function setHoraires(?string $horaires): self
    {
        $this->horaires = $horaires;

        return $this;
    }

    public function getSalaire(): ?string
    {
        return $this->salaire;
    }

    public function setSalaire(?string $salaire): self
    {
        $this->salaire = $salaire;

        return $this;
    }

    public function isValidationStatut(): ?bool
    {
        return $this->validation_statut;
    }

    public function setValidationStatut(bool $validation_statut): self
    {
        $this->validation_statut = $validation_statut;

        return $this;
    }
}

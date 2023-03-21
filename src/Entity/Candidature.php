<?php

namespace App\Entity;

use App\Repository\CandidatureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidatureRepository::class)]
class Candidature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $annonceId = null;

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCandidature = null;

    #[ORM\Column]
    private ?bool $validationCandidature = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $userNom = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $userPrenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userMail = null;

    #[ORM\Column(nullable: true)]
    private ?bool $userIsValid = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $annonceTitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $annonceNomEntreprise = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $annonceDate = null;

    #[ORM\Column(nullable: true)]
    private ?bool $annonceIsValid = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnonceId(): ?int
    {
        return $this->annonceId;
    }

    public function setAnnonceId(int $annonceId): self
    {
        $this->annonceId = $annonceId;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getDateCandidature(): ?\DateTimeInterface
    {
        return $this->dateCandidature;
    }

    public function setDateCandidature(\DateTimeInterface $dateCandidature): self
    {
        $this->dateCandidature = $dateCandidature;

        return $this;
    }

    public function isValidationCandidature(): ?bool
    {
        return $this->validationCandidature;
    }

    public function setValidationCandidature(bool $validationCandidature): self
    {
        $this->validationCandidature = $validationCandidature;

        return $this;
    }

    public function getUserNom(): ?string
    {
        return $this->userNom;
    }

    public function setUserNom(?string $userNom): self
    {
        $this->userNom = $userNom;

        return $this;
    }

    public function getUserPrenom(): ?string
    {
        return $this->userPrenom;
    }

    public function setUserPrenom(?string $userPrenom): self
    {
        $this->userPrenom = $userPrenom;

        return $this;
    }

    public function getUserMail(): ?string
    {
        return $this->userMail;
    }

    public function setUserMail(?string $userMail): self
    {
        $this->userMail = $userMail;

        return $this;
    }

    public function isUserIsValid(): ?bool
    {
        return $this->userIsValid;
    }

    public function setUserIsValid(?bool $userIsValid): self
    {
        $this->userIsValid = $userIsValid;

        return $this;
    }

    public function getAnnonceTitle(): ?string
    {
        return $this->annonceTitle;
    }

    public function setAnnonceTitle(?string $annonceTitle): self
    {
        $this->annonceTitle = $annonceTitle;

        return $this;
    }

    public function getAnnonceNomEntreprise(): ?string
    {
        return $this->annonceNomEntreprise;
    }

    public function setAnnonceNomEntreprise(?string $annonceNomEntreprise): self
    {
        $this->annonceNomEntreprise = $annonceNomEntreprise;

        return $this;
    }

    public function getAnnonceDate(): ?\DateTimeInterface
    {
        return $this->annonceDate;
    }

    public function setAnnonceDate(?\DateTimeInterface $annonceDate): self
    {
        $this->annonceDate = $annonceDate;

        return $this;
    }

    public function isAnnonceIsValid(): ?bool
    {
        return $this->annonceIsValid;
    }

    public function setAnnonceIsValid(?bool $annonceIsValid): self
    {
        $this->annonceIsValid = $annonceIsValid;

        return $this;
    }
}

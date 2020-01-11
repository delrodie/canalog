<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OperationRepository")
 */
class Operation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Type")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Mode")
     */
    private $mode;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Officine", inversedBy="operations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $officine;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $assurance;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(?int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getMode(): ?Mode
    {
        return $this->mode;
    }

    public function setMode(?Mode $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function getOfficine(): ?Officine
    {
        return $this->officine;
    }

    public function setOfficine(?Officine $officine): self
    {
        $this->officine = $officine;

        return $this;
    }

    public function getAssurance(): ?string
    {
        return $this->assurance;
    }

    public function setAssurance(?string $assurance): self
    {
        $this->assurance = $assurance;

        return $this;
    }
}

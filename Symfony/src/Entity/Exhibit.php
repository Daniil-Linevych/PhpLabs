<?php

namespace App\Entity;

use App\Repository\ExhibitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ExhibitRepository::class)]
#[ORM\Table(name: 'exhibits')]
class Exhibit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: TYPES::TEXT, nullable:true)]
    private ?string $description = null;

    #[ORM\Column(nullable:true)]
    private ?int $creationYear = null;

    #[ORM\Column(length:255)]
    private ?string $author = null;

    #[ORM\ManyToOne(targetEntity: Exhibition::class, inversedBy: 'exhibits')]
    #[ORM\JoinColumn(nullable: true)] 
    private ?Exhibition $exhibition = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getCreationYear(): ?int
    {
        return $this->creationYear;
    }

    public function setCreationYear(int $creationYear): static
    {
        $this->creationYear = $creationYear;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getExhibition(): ?Exhibition
    {
        return $this->exhibition;
    }

    public function setExhibition(Exhibition $exhibition): self
    {
        $this->exhibition = $exhibition;

        return $this;
    }

    
}

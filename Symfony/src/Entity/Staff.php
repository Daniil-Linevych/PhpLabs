<?php

namespace App\Entity;

use App\Repository\StaffRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: StaffRepository::class)]
class Staff
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $fullName = null;

    #[ORM\Column(length: 100)]
    private ?string $position = null;

    #[ORM\Column(length: 20)]
    private ?string $phone = null;

    #[ORM\Column]
    private ?float $salary = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $hireDate = null;

    #[ORM\ManyToMany(targetEntity: Exhibition::class, mappedBy:'staff')]
    private ?Collection $exhibitions;

    public function __construct()
    {
        $this->exhibitions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getSalary(): ?float
    {
        return $this->salary;
    }

    public function setSalary(float $salary): static
    {
        $this->salary = $salary;

        return $this;
    }

    public function getHireDate(): ?\DateTimeInterface
    {
        return $this->hireDate;
    }

    public function setHireDate(\DateTimeInterface $hireDate): static
    {
        $this->hireDate = $hireDate;

        return $this;
    }

    public function getExhibitions(): ?Collection{

        return $this->exhibitions;
    }

    public function getExhibitionsString(): string {

        if ($this->exhibitions->isEmpty()){
            return 'No one assigned!';
        }
        $exhibitions = array_map(fn($exhibition)=>$exhibition->getName(), $this->exhibitions->toArray());
        return implode(', ', $exhibitions);
    }

    public function addExhibition(?Exhibition $exhibition): self{

        if (!$this->exhibitions->contains($exhibition)){
            $this->exhibitions[] = $exhibition;
            $exhibition->addStaff($this);
        }

        return $this;
    }

    public function removeExhibition(?Exhibition $exhibition): self{

       if($this->exhibitions->removeElement($exhibition)){
            $exhibition->removeStaff($this);
        }

        return $this;
    }
}

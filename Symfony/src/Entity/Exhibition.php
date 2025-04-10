<?php

namespace App\Entity;

use App\Entity\Staff;
use App\Entity\Exhibit;
use App\Repository\ExhibitionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExhibitionRepository::class)]
#[ORM\Table(name: 'exhibitions')]
class Exhibition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $end_date = null;

    #[ORM\OneToMany(targetEntity: Exhibit::class, mappedBy: 'exhibitions')]
    private ?Collection $exhibits;

    #[ORM\OneToMany(targetEntity: Ticket::class, mappedBy:'exhibitions')]
    private ?Collection $tickets;

    #[ORM\ManyToMany(targetEntity: Staff::class, inversedBy: 'exhibitions')]
    private $staff;

    public function __construct(){
        $this->exhibits = new ArrayCollection();
        $this->staff = new ArrayCollection();
        $this->tickets = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): static
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getExhibits(): Collection{
        return $this->exhibits;
    }

    public function addExhibit(Exhibit $exhibit): static{

        if (!$this->exhibits->contains($exhibit)){
            $this->exhibits[] = $exhibit;
        }

        return $this;
    }

    public function removeExhibit(Exhibit $exhibit): static{
        
        $this->exhibits->removeElement($exhibit);

        return $this;
    }

    public function getTickets(): Collection{
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self{

        if (!$this->tickets->contains($ticket)){
            $this->tickets->add($ticket);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self{
        
        $this->tickets->removeElement($ticket);

        return $this;
    }

    public function getStaffMembers(): Collection{
        return $this->staff;
    }

    public function getStaffMembersString(): string{
        if ($this->staff->isEmpty()){
            return 'Nobody assigned!';
        }
        $names = array_map(fn($member)=> $member->getFullName(), $this->staff->toArray());
        return implode(', ', $names);
    }

    public function addStaff(Staff $staffMember): self
    {
        if (!$this->staff->contains($staffMember)) {
            $this->staff[] = $staffMember;
            $staffMember->addExhibition($this);
        }
        return $this;
    }

    public function removeStaff(Staff $staffMember): self
    {
        if ($this->staff->removeElement($staffMember)) {
            $staffMember->removeExhibition($this);  
        }

        return $this;
    }

}

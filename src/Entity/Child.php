<?php

namespace App\Entity;

use App\Repository\ChildRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChildRepository::class)]
class Child
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $class = null;

    #[ORM\Column(length: 255)]
    private ?string $age = null;

    #[ORM\ManyToOne(inversedBy: 'children')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $parent = null;

    /**
     * @var Collection<int, Signup>
     */
    #[ORM\OneToMany(targetEntity: Signup::class, mappedBy: 'child', cascade: ['persist', 'remove'])]
    private Collection $signups;


    public function __construct()
    {
        $this->signups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(string $class): static
    {
        $this->class = $class;

        return $this;
    }

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(string $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getParent(): ?User
    {
        return $this->parent;
    }

    public function setParent(?User $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, Signup>
     */
    public function getSignups(): Collection
    {
        return $this->signups;
    }

    public function addSignup(Signup $signup): static
    {
        if (!$this->signups->contains($signup)) {
            $this->signups->add($signup);
            $signup->setChild($this);
        }

        return $this;
    }

    public function removeSignup(Signup $signup): static
    {
        if ($this->signups->removeElement($signup)) {
            $signup->setChild(null);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}

<?php

namespace App\Entity;

use App\Repository\ActivityDateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityDateRepository::class)]
class ActivityDate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'activityDates')]
    private ?Activity $Activity = null;

    /**
     * @var Collection<int, Signup>
     */
    #[ORM\OneToMany(targetEntity: Signup::class, mappedBy: 'activityDate', cascade: ['persist', 'remove'])]
    private Collection $signups;

    public function __construct()
    {
        $this->signups = new ArrayCollection();
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
            $signup->setActivityDate($this);
        }

        return $this;
    }

    public function removeSignup(Signup $signup): static
    {
        if ($this->signups->removeElement($signup)) {
            // set the owning side to null (unless already changed)
            if ($signup->getActivityDate() === $this) {
                $signup->setActivityDate(null);
            }
        }

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getActivity(): ?Activity
    {
        return $this->Activity;
    }

    public function setActivity(?Activity $Activity): static
    {
        $this->Activity = $Activity;

        return $this;
    }
}

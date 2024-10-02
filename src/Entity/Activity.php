<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    /**
     * @Assert\File(
     *     maxSize="10M",
     *     maxSizeMessage="Bestand is te groot: {{limit}}.",
     *     mimeTypes = {"image/jpeg", "image/png"},
     *     mimeTypesMessage = "File must be a JPG or PNG."
     * )
     */
    private ?string $image = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    private ?TargetGroup $targetGroup = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    private ?School $school = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    private ?User $creator = null;

    /**
     * @var Collection<int, ActivityDate>
     */
    #[ORM\OneToMany(targetEntity: ActivityDate::class, mappedBy: 'Activity', cascade: ['persist', 'remove'])]
    private Collection $activityDates;

    public function __construct()
    {
        $this->activityDates = new ArrayCollection();
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getTargetGroup(): ?TargetGroup
    {
        return $this->targetGroup;
    }

    public function setTargetGroup(?TargetGroup $targetGroup): static
    {
        $this->targetGroup = $targetGroup;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getSchool(): ?School
    {
        return $this->school;
    }

    public function setSchool(?School $school): static
    {
        $this->school = $school;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): static
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * @return Collection<int, ActivityDate>
     */
    public function getActivityDates(): Collection
    {
        return $this->activityDates;
    }

    public function addActivityDate(ActivityDate $activityDate): static
    {
        if (!$this->activityDates->contains($activityDate)) {
            $this->activityDates->add($activityDate);
            $activityDate->setActivity($this);
        }

        return $this;
    }

    public function removeActivityDate(ActivityDate $activityDate): static
    {
        if ($this->activityDates->removeElement($activityDate)) {
            // set the owning side to null (unless already changed)
            if ($activityDate->getActivity() === $this) {
                $activityDate->setActivity(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\SignupRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SignupRepository::class)]
class Signup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'signups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Child $child = null;

    #[ORM\ManyToOne(inversedBy: 'signups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ActivityDate $activityDate = null;

    #[ORM\Column]
    private ?\DateTime $signedUpAt = null;

    #[ORM\ManyToOne(inversedBy: 'signups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChild(): ?Child
    {
        return $this->child;
    }

    public function setChild(?Child $child): static
    {
        $this->child = $child;

        return $this;
    }

    public function getActivityDate(): ?ActivityDate
    {
        return $this->activityDate;
    }

    public function setActivityDate(?ActivityDate $activityDate): static
    {
        $this->activityDate = $activityDate;

        return $this;
    }

    public function getSignedUpAt(): ?\DateTime
    {
        return $this->signedUpAt;
    }

    public function setSignedUpAt(\DateTime $signedUpAt): static
    {
        $this->signedUpAt = $signedUpAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SessionRepository::class)
 */
class Session
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=160)
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotNull
     */
    private $startTime;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotNull
     */
    private $endTime;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $track = 'General';

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $status = 'scheduled';

    /**
     * @ORM\ManyToOne(targetEntity=Conference::class, inversedBy="sessions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $conference;

    /**
     * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="sessions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $room;

    /**
     * @ORM\ManyToMany(targetEntity=Speaker::class, inversedBy="sessions")
     * @ORM\JoinTable(name="session_speakers")
     */
    private $speakers;

    /**
     * @ORM\OneToMany(targetEntity=Registration::class, mappedBy="session", orphanRemoval=true)
     */
    private $registrations;

    public function __construct()
    {
        $this->speakers = new ArrayCollection();
        $this->registrations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getTrack(): ?string
    {
        return $this->track;
    }

    public function setTrack(string $track): self
    {
        $this->track = $track;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getConference(): ?Conference
    {
        return $this->conference;
    }

    public function setConference(?Conference $conference): self
    {
        $this->conference = $conference;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    /**
     * @return Collection|Speaker[]
     */
    public function getSpeakers(): Collection
    {
        return $this->speakers;
    }

    public function addSpeaker(Speaker $speaker): self
    {
        if (!$this->speakers->contains($speaker)) {
            $this->speakers[] = $speaker;
        }

        return $this;
    }

    public function removeSpeaker(Speaker $speaker): self
    {
        $this->speakers->removeElement($speaker);

        return $this;
    }

    /**
     * @return Collection|Registration[]
     */
    public function getRegistrations(): Collection
    {
        return $this->registrations;
    }
}

<?php

namespace App\Entity;

use App\Repository\RegistrationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RegistrationRepository::class)
 * @ORM\Table(name="registrations", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="unique_user_session", columns={"user_id", "session_id"})
 * })
 */
class Registration
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="registrations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Session::class, inversedBy="registrations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $session;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registeredAt;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $status = 'confirmed';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function getRegisteredAt(): ?\DateTimeInterface
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(\DateTimeInterface $registeredAt): self
    {
        $this->registeredAt = $registeredAt;

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
}

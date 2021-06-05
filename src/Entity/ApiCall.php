<?php

namespace App\Entity;

use App\Repository\ApiCallRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApiCallRepository::class)
 */
class ApiCall
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $countHit;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateFirstHit;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateLastHit;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="apiCalls")
     */
    private $users_id;

    /**
     * @ORM\ManyToOne(targetEntity=Area::class, inversedBy="apiCalls")
     */
    private $areas_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountHit(): ?int
    {
        return $this->countHit;
    }

    public function setCountHit(?int $countHit): self
    {
        $this->countHit = $countHit;

        return $this;
    }

    public function getDateFirstHit(): ?\DateTimeInterface
    {
        return $this->dateFirstHit;
    }

    public function setDateFirstHit(?\DateTimeInterface $dateFirstHit): self
    {
        $this->dateFirstHit = $dateFirstHit;

        return $this;
    }

    public function getDateLastHit(): ?\DateTimeInterface
    {
        return $this->dateLastHit;
    }

    public function setDateLastHit(?\DateTimeInterface $dateLastHit): self
    {
        $this->dateLastHit = $dateLastHit;

        return $this;
    }

    public function getUsersId(): ?User
    {
        return $this->users_id;
    }

    public function setUsersId(?User $users_id): self
    {
        $this->users_id = $users_id;

        return $this;
    }

    public function getAreasId(): ?Area
    {
        return $this->areas_id;
    }

    public function setAreasId(?Area $areas_id): self
    {
        $this->areas_id = $areas_id;

        return $this;
    }
}

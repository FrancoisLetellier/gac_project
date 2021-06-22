<?php

namespace App\Entity;

use App\Repository\CustomersCallRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CustomersCallRepository::class)
 */
class CustomersCall
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $call_date;

    /**
     * @ORM\Column(type="time")
     */
    private $real_duration;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCallDate(): ?\DateTimeInterface
    {
        return $this->call_date;
    }

    public function setCallDate(\DateTimeInterface $call_date): self
    {
        $this->call_date = $call_date;

        return $this;
    }

    public function getRealDuration(): ?\DateTimeInterface
    {
        return $this->real_duration;
    }

    public function setRealDuration(\DateTimeInterface $real_duration): self
    {
        $this->real_duration = $real_duration;

        return $this;
    }
}

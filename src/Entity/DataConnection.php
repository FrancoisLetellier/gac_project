<?php

namespace App\Entity;

use App\Repository\DataConnectionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DataConnectionRepository::class)
 */
class DataConnection
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $data_charge;

    /**
     * @ORM\Column(type="time")
     */
    private $time_connection;

    /**
     * @ORM\Column(type="integer")
     */
    private $subscriber;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDataCharge(): ?float
    {
        return $this->data_charge;
    }

    public function setDataCharge(float $data_charge): self
    {
        $this->data_charge = $data_charge;

        return $this;
    }

    public function getTimeConnection(): ?\DateTimeInterface
    {
        return $this->time_connection;
    }

    public function setTimeConnection(\DateTimeInterface $time_connection): self
    {
        $this->time_connection = $time_connection;

        return $this;
    }

    public function getSubscriber(): ?int
    {
        return $this->subscriber;
    }

    public function setSubscriber(int $subscriber): self
    {
        $this->subscriber = $subscriber;

        return $this;
    }
}

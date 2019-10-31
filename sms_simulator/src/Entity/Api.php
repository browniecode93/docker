<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApiRepository")
 */
class Api
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $api_call;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $api_status;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $number;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApiCall(): ?string
    {
        return $this->api_call;
    }

    public function setApiCall(?string $api_call): self
    {
        $this->api_call = $api_call;

        return $this;
    }

    public function getApiStatus(): ?string
    {
        return $this->api_status;
    }

    public function setApiStatus(?string $api_status): self
    {
        $this->api_status = $api_status;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): self
    {
        $this->number = $number;

        return $this;
    }
}

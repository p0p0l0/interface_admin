<?php

namespace App\Entity;

use App\Repository\WebsiteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WebsiteRepository::class)
 */
class Website
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ipServeur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nameFolder;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pathFolder;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="websites")
     */
    private $client;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getIpServeur(): ?string
    {
        return $this->ipServeur;
    }

    public function setIpServeur(string $ipServeur): self
    {
        $this->ipServeur = $ipServeur;

        return $this;
    }

    public function getNameFolder(): ?string
    {
        return $this->nameFolder;
    }

    public function setNameFolder(string $nameFolder): self
    {
        $this->nameFolder = $nameFolder;

        return $this;
    }

    public function getPathFolder(): ?string
    {
        return $this->pathFolder;
    }

    public function setPathFolder(string $pathFolder): self
    {
        $this->pathFolder = $pathFolder;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }


}

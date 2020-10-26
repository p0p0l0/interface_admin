<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TypeRepository::class)
 */
class Type
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Ip
     */
    private $IpServer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Website::class, mappedBy="type")
     */
    private $Websites;

    public function __construct()
    {
        $this->Websites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIpServer(): ?string
    {
        return $this->IpServer;
    }

    public function setIpServer(string $IpServer): self
    {
        $this->IpServer = $IpServer;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
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

    /**
     * @return Collection|Website[]
     */
    public function getWebsites(): Collection
    {
        return $this->Websites;
    }

    public function addWebsite(Website $website): self
    {
        if (!$this->Websites->contains($website)) {
            $this->Websites[] = $website;
            $website->setType($this);
        }

        return $this;
    }

    public function removeWebsite(Website $website): self
    {
        if ($this->Websites->contains($website)) {
            $this->Websites->removeElement($website);
            // set the owning side to null (unless already changed)
            if ($website->getType() === $this) {
                $website->setType(null);
            }
        }

        return $this;
    }
}

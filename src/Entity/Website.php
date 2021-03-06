<?php

namespace App\Entity;

use App\Repository\WebsiteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=WebsiteRepository::class)
 * @UniqueEntity("serverName")
 * @ORM\HasLifecycleCallbacks()
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
     * @Assert\Regex("/^[a-zA-Z0-9\-\_]+$/")
     */
    private $serverName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex("/^[a-zA-Z0-9\-\_\.]+$/")
     */
    private $nameFolder;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class)
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class)
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getServerName(): ?string
    {
        return $this->serverName;
    }

    public function setServerName(string $serverName): self
    {
        $this->serverName = $serverName;

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


    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }


    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @ORM\PreFlush
     */
    public function setNameFolderValue(){
        $this->nameFolder = $this->serverName.'.'.$this->type->getServerName();
    }
    
}

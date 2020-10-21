<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * @UniqueEntity("name", message="Un client existe déjà avec ce nom")
 * @ORM\HasLifecycleCallbacks()
 */
class Customer
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
     * @Assert\Email(message="L'email n'est pas valide")
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $userCreation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $editAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userEdit;

    /**
     * @ORM\OneToMany(targetEntity=Website::class, mappedBy="customer")
     */
    private $websites;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    public function __construct()
    {
        $this->websites = new ArrayCollection();

    }

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

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUserCreation(): ?string
    {
        return $this->userCreation;
    }

    public function setUserCreation(string $userCreation): self
    {
        $this->userCreation = $userCreation;

        return $this;
    }

    public function getEditAt(): ?\DateTimeInterface
    {
        return $this->editAt;
    }

    public function setEditAt(?\DateTimeInterface $editAt): self
    {
        $this->editAt = $editAt;

        return $this;
    }

    public function getUserEdit(): ?string
    {
        return $this->userEdit;
    }

    public function setUserEdit(?string $userEdit): self
    {
        $this->userEdit = $userEdit;

        return $this;
    }

    /**
     * @return Collection|Website[]
     */
    public function getWebsites(): Collection
    {
        return $this->websites;
    }

    public function addWebsite(Website $website): self
    {
        if (!$this->websites->contains($website)) {
            $this->websites[] = $website;
            $website->setCustomer($this);
        }

        return $this;
    }

    public function removeWebsite(Website $website): self
    {
        if ($this->websites->contains($website)) {
            $this->websites->removeElement($website);
            // set the owning side to null (unless already changed)
            if ($website->getCustomer() === $this) {
                $website->setCustomer(null);
            }
        }

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

     /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
    }

   /**
     * @ORM\PreFlush
     */
    public function setEditAtValue(){
        $this->editAt = new \DateTime();
    }

}
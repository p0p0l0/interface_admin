<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=TypeRepository::class)
 * @UniqueEntity("name")
 * @ORM\HasLifecycleCallbacks()
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
     * @Assert\Regex("/^[a-zA-Z0-9\-\_]+$/")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Ip
     * @Assert\Regex("/^[0-9\.]+$/")
     */
    private $IpServer;
    
    /**
     * @ORM\Column(type="integer")
     * @Assert\Regex("/^[0-9]+$/")
     */
    private $port = 22;
     
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex("/^[a-zA-Z0-9\-\_]+$/")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex("/^[a-zA-Z0-9\-\_]+$/")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path ='/var/www/';

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex("/^[a-zA-Z0-9\-\_]+$/")
     */
    private $serverName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $scriptCreate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $scriptMaj;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $scriptDelete;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $scriptUpdate;


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
    public function getIpServer(): ?string
    {
        return $this->IpServer;
    }

    public function setIpServer(string $IpServer): self
    {
        $this->IpServer = $IpServer;

        return $this;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function setPort(int $port): self
    {
        $this->port = $port;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    public function getServerName(): ?string
    {
        return $this->serverName;
    }

    public function setServerName(string $serverName): self
    {
        $this->serverName = $serverName;

        return $this;
    }

    public function getScriptCreate(): ?string
    {
        return $this->scriptCreate;
    }

    public function setScriptCreate(string $scriptCreate): self
    {
        $this->scriptCreate = $scriptCreate;

        return $this;
    }

    public function getScriptMaj(): ?string
    {
        return $this->scriptMaj;
    }

    public function setScriptMaj(string $scriptMaj): self
    {
        $this->scriptMaj = $scriptMaj;

        return $this;
    }

    public function getScriptDelete(): ?string
    {
        return $this->scriptDelete;
    }

    public function setScriptDelete(string $scriptDelete): self
    {
        $this->scriptDelete = $scriptDelete;

        return $this;
    }

    public function getScriptUpdate(): ?string
    {
        return $this->scriptUpdate;
    }

    public function setScriptUpdate(string $scriptUpdate): self
    {
        $this->scriptUpdate = $scriptUpdate;

        return $this;
    }


    /**
     * @ORM\PrePersist
     */
    public function prePersistScriptValue()
    {
        $this->scriptCreate = "install-".mb_strtolower($this->getName()).".sh";
        $this->scriptUpdate = "update-".mb_strtolower($this->getName()).".sh";
        $this->scriptMaj = "maj-".mb_strtolower($this->getName()).".sh";
        $this->scriptDelete = "delete-".mb_strtolower($this->getName()).".sh";
    }



}

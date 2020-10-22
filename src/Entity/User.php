<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    //region id: UuidInterface

    /**
     * @var UuidInterface
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     */
    private $id;

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    //endregion

    //region username: string

    /**
     * @var string
     * @ORM\Column(type="string", length=16, unique=true)
     */
    private $username;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    //endregion

    //region password: string

    /**
     * @var string
     * @ORM\Column(type="string", length=1024)
     */
    private $password;

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    //endregion

    //region email: string

    /**
     * @var string
     * @ORM\Column(type="string", length=512)
     */
    private $email;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    //endregion

    //region firstname: string

    /**
     * @var string
     * @ORM\Column(type="string", length=64)
     */
    private $firstname;

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    //endregion

    //region lastname: string

    /**
     * @var string
     * @ORM\Column(type="string", length=64)
     */
    private $lastname;

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }

    //endregion

    //region phoneNumber: ?string

    /**
     * @var ?string
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $phoneNumber;

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    //endregion

    //region picture: ?string

    /**
     * @var ?string
     * @ORM\Column(type="string", length=4096, nullable=true)
     */
    private $picture;

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;
        return $this;
    }

    //endregion

    //region activateUntil: ?DateTime

    /**
     * @var DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $activateUntil;

    public function setActivateUntil(?DateTime $activateUntil): self
    {
        $this->activateUntil = $activateUntil;
        return $this;
    }

    public function getActivateUntil(): ?DateTime
    {
        return $this->activateUntil;
    }

    //endregion

    //region UserInterface

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials(): void
    {}

    //endregion

    public function __construct()
    {
        $this->id = Uuid::uuid1();
    }
}

<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\MappedSuperclass()
 * @ORM\HasLifecycleCallbacks()
 */
abstract class AbstractEntity
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

    //region createdAt: DateTime

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    //endregion

    //region updatedAt: ?DateTime

    /**
     * @var DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    //endregion

    public function __construct()
    {
        $this->id = Uuid::uuid1();
        $this->createdAt = new DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updatedAt = new DateTime();
    }
}

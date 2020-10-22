<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fresh\DoctrineEnumBundle\Validator\Constraints\Enum;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRatingRepository")
 */
class UserRating extends AbstractEntity
{
    //region user: User

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    //endregion

    //region author: User

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $author;

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;
        return $this;
    }

    //endregion

    //region type: string (RatingType)

    /**
     * @var string
     * @ORM\Column(type="RatingType")
     * @Enum(entity="App\DBAL\Types\RatingType")
     */
    private $type;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    //endregion
}

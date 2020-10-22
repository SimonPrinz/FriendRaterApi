<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserCommentRepository")
 */
class UserComment extends AbstractEntity
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

    //region isAnonymous: boolean

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $isAnonymous;

    public function isAnonymous(): bool
    {
        return $this->isAnonymous;
    }

    public function setIsAnonymous(bool $isAnonymous): self
    {
        $this->isAnonymous = $isAnonymous;
        return $this;
    }

    //endregion

    //region comment: string

    /**
     * @var string
     * @ORM\Column(type="string", length=2048)
     */
    private $comment;

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;
        return $this;
    }

    //endregion
}

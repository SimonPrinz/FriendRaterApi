<?php

namespace App\Repository;

use App\Entity\UserComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserComment[] findAll()
 * @method UserComment[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserComment::class);
    }
}

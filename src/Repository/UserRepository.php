<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[] findAll()
 * @method User[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param string $usernameOrEmailOrPhoneNumber
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function loadUser(string $usernameOrEmailOrPhoneNumber)
    {
        return $this->createQueryBuilder('user')
            ->select('user')
            ->where('user.username = :value')
            ->orWhere('user.email = :value')
            ->orWhere('user.phoneNumber = :value')
            ->andWhere('user.activateUntil is null')
            ->setParameters([
                'value' => $usernameOrEmailOrPhoneNumber,
            ])
            ->getQuery()->getOneOrNullResult();
    }
}

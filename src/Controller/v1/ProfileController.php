<?php

namespace App\Controller\v1;

use App\DBAL\Types\RatingType;
use App\Entity\UserComment;
use App\Entity\UserRating;
use App\Repository\UserRepository;
use App\Subscribers\ApiException;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileController extends AbstractController
{
    /**
     * @Route("/v1/profile", methods={"GET"}, name="app_v1_profile")
     */
    public function profile(
        ?UuidInterface $id,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        UserInterface $user
    ): Response {
        if ($id != null) {
            $user = $userRepository->findOneBy([
                'id' => $id,
            ]);
        }
        if ($user == null) {
            throw new ApiException([], 404);
        }

        $query = $entityManager->createQueryBuilder()
            ->select(['user_rating.type', 'count(user_rating.user) as count'])
            ->from(UserRating::class, 'user_rating')
            ->where('user_rating.user = :user')
            ->groupBy('user_rating.type')
            ->getQuery();
        $result = $query
            ->setParameter('user', $user->getId()->getBytes())
            ->getResult();

        $getRatingsForType = function($type) use ($result): int {
            /** @var RatingType $ratingType */
            $ratingType = RatingType::getType('RatingType');

            foreach ($result as $res) {
                if ($ratingType::getReadableValue($res['type']) == $type) {
                    return $res['count'];
                }
            }
            return 0;
        };

        return $this->json([
            'name' => $user->getFirstname() . ' ' . $user->getLastname(),
            'picture' => $user->getPicture(),
            'ratings' => [
                'up' => $getRatingsForType(RatingType::UP),
                'down' => $getRatingsForType(RatingType::DOWN),
                'like' => $getRatingsForType(RatingType::LIKE),
            ],
        ]);
    }

    /**
     * @Route("/v1/comments", methods={"GET"}, name="app_v1_comments")
     */
    public function comments(
        ?UuidInterface $id,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ): Response {
        $user = $userRepository->findOneBy([
            'id' => $id,
        ]);
        if ($user == null) {
            throw new ApiException([], 404);
        }

        $comments = $entityManager->getRepository(UserComment::class)
            ->findBy([
                'user' => $user,
            ]);

        return $this->json(array_map(function($comment) {
            return [
                'comment' => $comment->getComment(),
                'from' => $comment->isAnonymous() ? null : [
                    'id' => $comment->getAuthor()->getId(),
                    'name' => $comment->getAuthor()->getFirstname(),
                ],
            ];
        }, $comments));
    }
}

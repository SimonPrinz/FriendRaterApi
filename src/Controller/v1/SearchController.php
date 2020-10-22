<?php

namespace App\Controller\v1;

use App\Entity\User;
use App\Subscribers\ApiException;
use Brick\PhoneNumber\PhoneNumber;
use Brick\PhoneNumber\PhoneNumberFormat;
use Brick\PhoneNumber\PhoneNumberParseException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class SearchController extends AbstractController
{
    /**
     * @Route("/v1/search", methods={"GET"}, name="app_v1_search")
     */
    public function search(Request $request, EntityManagerInterface $entityManager, UserInterface $user): Response
    {
        /** @var User $user */

        if (!$request->query->has('q')) {
            throw new ApiException([new Exception('no query was specified')], 400);
        }

        $search = $request->query->get('q');
        if (empty($search)) {
            throw new ApiException([new Exception('query cannot be empty')], 400);
        }
        if (strlen($search) < 3) {
            throw new ApiException([new Exception('query length must be greater than two')], 400);
        }

        $builder = $entityManager->createQueryBuilder()
            ->select(['user'])
            ->from(User::class, 'user')
            ->where('user.email = :query')
            ->orWhere('user.firstname like :likeQuery')
            ->orWhere('user.lastname like :likeQuery')
            ->orWhere('concat(user.firstname, \' \', user.lastname) like :likeQuery')
            ->orWhere('user.username like :likeQuery')
            ->andWhere('user.id <> :myId')
            ->setMaxResults(10);
        $query = $builder->getQuery()
            ->setParameters([
                'query' => $search,
                'likeQuery' => $search . '%',
                'myId' => $user->getId()->getBytes(),
            ]);
        $results = $query->getResult();

        return $this->json($this->formatUsers($results));
    }

    /**
     * @Route("/v1/numbersearch", methods={"POST"}, name="app_v1_numbersearch")
     */
    public function numberSearch(array $jsonData, EntityManagerInterface $entityManager, UserInterface $user): Response
    {
        /** @var User $user */

        if (!is_array($jsonData)) {
            throw new ApiException([new Exception('post data needs to be json array')], 400);
        }

        $numbers = array_filter(array_map(function ($i) {
            try {
                $number = PhoneNumber::parse($i);
                return $number->format(PhoneNumberFormat::E164);
            } catch (PhoneNumberParseException $exception) {
                return null;
            }
        }, $jsonData));

        $builder = $entityManager->createQueryBuilder()
            ->select(['user'])
            ->from(User::class, 'user')
            ->where('user.phoneNumber in (:numbers)')
            ->andWhere('user.id <> :myId')
            ->setMaxResults(count($numbers));
        $query = $builder->getQuery()
            ->setParameters([
                'numbers' => $numbers,
                'myId' => $user->getId()->getBytes(),
            ]);
        $results = $query->getResult();

        return $this->json($this->formatUsers($results));
    }

    private function formatUsers(array $users): array
    {
        return array_map(function(User $i) {
            return [
                'id' => $i->getId(),
                'username' => $i->getUsername(),
                'name' => $i->getFirstname() . ' ' . $i->getLastname(),
            ];
        }, $users);
    }
}

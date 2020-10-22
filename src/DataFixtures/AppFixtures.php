<?php

namespace App\DataFixtures;

use App\DBAL\Types\RatingType;
use App\Entity\User;
use App\Entity\UserComment;
use App\Entity\UserCommentRating;
use App\Entity\UserRating;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private const USERS = 20;

    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $users = [];
        for ($i = 0; $i < self::USERS; $i++) {
            ($users[$i] = $user = new User())
                ->setUsername('User' . $i)
                ->setEmail('user' . $i . '@friendraterapi.simonprinz.me')
                ->setFirstname('User' . $i)
                ->setLastname('FRApi');
            $user->setPassword($this->passwordEncoder->encodePassword($user, str_repeat($i, 10)));
            $manager->persist($user);
        }

        for ($i = 0; $i < count($users); $i++) {
            $ratingCount = random_int(0, 10);
            $user = $users[$i];
            for ($j = 0; $j < $ratingCount; $j++) {
                ($userRating = new UserRating())
                    ->setUser($user)
                    ->setAuthor($this->getRandom($users, [$user]))
                    ->setType($this->getRandom(RatingType::getChoices()));
                $manager->persist($userRating);
            }
        }

        for ($i = 0; $i < count($users); $i++) {
            $commentCount = random_int(0, 5);
            $user = $users[$i];
            for ($j = 0; $j < $commentCount; $j++) {
                ($userComment = new UserComment())
                    ->setUser($user)
                    ->setAuthor($this->getRandom($users, [$user]))
                    ->setComment('Lorem ipsum dolor sit amet.')
                    ->setIsAnonymous(random_int(0, 1) === 0);
                $manager->persist($userComment);
                $commentRatingCount = random_int(0, 5);
                for ($k = 0; $k < $commentRatingCount; $k++) {
                    ($userCommentRating = new UserCommentRating())
                        ->setUserComment($userComment)
                        ->setAuthor($this->getRandom($users, [$user]))
                        ->setType($this->getRandom(RatingType::getChoices()));
                    $manager->persist($userCommentRating);
                }
            }
        }

        $manager->flush();
    }

    private function getRandom(array $choices, array $exclude = []) {
        return in_array($choice = $choices[array_rand($choices)], $exclude)
            ? $this->getRandom($choices, $exclude)
            : $choice;
    }
}

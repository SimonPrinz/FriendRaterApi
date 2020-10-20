<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Register;
use App\Form\Type\RegisterType;
use App\Repository\UserRepository;
use App\Subscribers\ApiException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class AuthenticationController extends AbstractController
{
    /**
     * @Route("/login", methods={"GET"})
     */
    public function login(Security $security): Response
    {
        /** @var User $user */
        $user = $security->getUser();

        return $this->json([
            'name' => $user->getFirstname(),
        ]);
    }

    /**
     * @Route("/register", methods={"POST"})
     */
    public function register(EntityManagerInterface $entityManager, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, array $jsonData): Response
    {
        $register = new Register($userRepository);
        $form = $this->createForm(RegisterType::class, $register, ['csrf_protection' => false]);
        $form->submit($jsonData);
        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new ApiException($this->getErrors($form), 400);
        }
        ($user = new User())
            ->setUsername($register->username)
            ->setEmail($register->email)
            ->setFirstname($register->firstname)
            ->setLastname($register->lastname)
            ->setPhoneNumber($register->phoneNumber);
        $user->setPassword($passwordEncoder->encodePassword($user, $register->password));
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->json(true, 201);
    }

    private function getErrors(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = new Exception($error->getOrigin()->getName() . ': ' . $error->getMessage());
        }
        return $errors;
    }
}

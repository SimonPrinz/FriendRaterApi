<?php

namespace App\Controller\v1;

use App\Entity\User;
use App\Form\Register;
use App\Form\Type\RegisterType;
use App\Subscribers\ApiException;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationController extends AbstractController
{
    /**
     * @Route("/v1/login", methods={"GET"}, name="app_v1_login")
     */
    public function login(?UserInterface $user): Response
    {
        if ($user == null) {
            throw new ApiException([new Exception('login is required')], 401);
        }

        return $this->json([
            'name' => $user->getFirstname(),
        ]);
    }

    /**
     * @Route("/v1/register", methods={"POST"}, name="app_v1_register")
     */
    public function register(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder,
        MailerInterface $mailer,
        UrlGeneratorInterface $urlGenerator,
        array $jsonData
    ): Response {
        $userRepository = $entityManager->getRepository(User::class);
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
            ->setPhoneNumber($register->phoneNumber)
            ->setActivateUntil((new DateTime())->add(DateInterval::createFromDateString('30 minutes')));
        $user->setPassword($passwordEncoder->encodePassword($user, $register->password));
        ($email = new TemplatedEmail())
            ->to($user->getEmail())
            ->subject('Thank you for signing up!')
            ->htmlTemplate('emails/signup.html.twig')
            ->textTemplate('emails/signup.txt.twig')
            ->context([
                'name' => $user->getFirstname(),
                'username' => $user->getUsername(),
                'mail' => $user->getEmail(),
                'activation_link' => $urlGenerator->generate('app_v1_activate', [
                    'u' => $user->getUsername(),
                    'h' => $this->createHash($user),
                ], UrlGeneratorInterface::ABSOLUTE_URL),
                'expiration_date' => $user->getActivateUntil(),
            ]);
        try {
            $mailer->send($email);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->json(true, 201);
        } catch (Exception $exception) {
            throw new ApiException([$exception], 503);
        }
    }

    /**
     * @Route("/v1/activate", methods={"GET"}, name="app_v1_activate")
     */
    public function activate(Request $request, EntityManagerInterface $entityManager): Response
    {
        $username = $request->query->get('u');
        $hash = $request->query->get('h');
        if (!$username || !$hash) {
            throw new ApiException([], 400);
        }
        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy([
            'username' => $username,
        ]);
        if (!$user || $user->getActivateUntil() == null || $hash != $this->createHash($user)) {
            throw new ApiException([], 400);
        }
        $user->setActivateUntil(null);
        $entityManager->flush();

        return $this->render('v1/activate.html.twig');
    }

    private function createHash(User $user): string
    {
        return sha1(md5($user->getUsername() . ':' . $user->getEmail() . ':' . $user->getActivateUntil()->format(DateTime::ISO8601)));
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

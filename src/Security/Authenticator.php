<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class Authenticator extends AbstractGuardAuthenticator
{
    private const AUTHORIZATION_HEADER = 'Authorization';
    private const AUTHORIZATION_METHOD = 'Basic';

    /** @var UserRepository */
    private $userRepository;
    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request)
    {
        return $this->getCredentials($request) !== null;
    }

    public function getCredentials(Request $request): ?array
    {
        if (!$request->headers->has(self::AUTHORIZATION_HEADER)) {
            return null;
        }

        $authorizationHeader = $request->headers->get(self::AUTHORIZATION_HEADER);
        if (strpos($authorizationHeader, self::AUTHORIZATION_METHOD . ' ') !== 0) {
            return null;
        }

        $authorizationHeader = substr($authorizationHeader, strlen(self::AUTHORIZATION_METHOD . ' '));
        if (!($authorizationHeader = base64_decode($authorizationHeader)) ||
            !strpos($authorizationHeader, ':')) {
            return null;
        }

        [$username, $password] = explode(':', $authorizationHeader, 2);
        return [
            'username' => $username,
            'password' => $password,
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider): ?User
    {
        if ($credentials == null || empty($username = $credentials['username'])) {
            return null;
        }

        try {
            return $this->userRepository->loadUser($username);
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        if ($credentials == null || empty($password = $credentials['password'])) {
            return false;
        }

        return $this->passwordEncoder->isPasswordValid($user, $password);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        throw $exception;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        throw $authException;
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}

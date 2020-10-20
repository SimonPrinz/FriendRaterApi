<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Security\Core\Security;

class UserArgumentResolver implements ArgumentValueResolverInterface
{
    /** @var Security */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return $argument->getType() == User::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        if (($user = $this->security->getUser()) === null) {
            return [];
        }
        return [$user];
    }
}

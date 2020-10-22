<?php

namespace App\Controller\Resolver;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class UuidArgumentResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return $argument->getType() == UuidInterface::class &&
            in_array($argument->getName(), [
                'id',
                'uuid',
            ]);
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        if (($value = $request->get($argument->getName())) === null) {
            if ($argument->isNullable()) {
                yield null;
            } else {
                yield Uuid::fromString(Uuid::NIL);
            }
            return;
        }

        yield Uuid::fromString($value);
    }
}

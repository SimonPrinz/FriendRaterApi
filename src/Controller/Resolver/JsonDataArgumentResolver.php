<?php

namespace App\Controller\Resolver;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class JsonDataArgumentResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return !in_array($request->getMethod(), ['GET', 'HEAD', 'OPTIONS']) &&
            strpos(strtolower($request->headers->get('Content-Type')), 'application/json') === 0 &&
            $argument->getType() == 'array' &&
            strtolower($argument->getName()) === 'jsondata';
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        try {
            $content = $request->getContent();
            return [json_decode($content, true)];
        } catch (Exception $exception) {
            return null;
        }
    }
}

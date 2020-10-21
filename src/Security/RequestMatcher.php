<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

class RequestMatcher implements RequestMatcherInterface
{
    public function matches(Request $request)
    {
        return !preg_match('/^(\/v\d+\/(register|docs))/', $request->getPathInfo());
    }
}

<?php

namespace App\Controller\v1;

use App\Subscribers\ApiException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/v1", methods={"GET"}, name="app_v1_base")
     */
    public function index(UrlGeneratorInterface $urlGenerator): Response
    {
        throw new ApiException([new Exception('documentation is located at: ' . $urlGenerator->generate('app_v1_docs', [], UrlGeneratorInterface::ABSOLUTE_URL))], 400);
    }
}

<?php

namespace App\Controller\v1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DocumentationController extends AbstractController
{
    /**
     * @Route("/v1/docs", methods={"GET"}, name="app_v1_docs")
     */
    public function docs(UrlGeneratorInterface $urlGenerator): Response
    {
        return $this->render('docs.html.twig', [
            'api_url' => $urlGenerator->generate('app_v1_docs_yaml'),
        ]);
    }

    /**
     * @Route("/v1/docs/yaml", methods={"GET"}, name="app_v1_docs_yaml")
     */
    public function docsYaml(UrlGeneratorInterface $urlGenerator): Response
    {
        return $this->render('v1.yaml.twig', [
            'baseUrl' => $urlGenerator->generate('app_v1_base', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
    }
}

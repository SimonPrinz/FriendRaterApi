<?php

namespace App\Subscribers;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiResponseSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    /** @var bool */
    private $dev;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->dev = $parameterBag->get('kernel.environment') !== 'prod';
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if (!($exception instanceof ApiException)) {
            $response = new JsonResponse([
                [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                    'details' => $this->dev ? $exception->getTraceAsString() : null,
                ],
            ], 500);
            $event->setResponse($response);
            return;
        }

        /** @var ApiException $exception */

        $response = new JsonResponse(array_map(function ($e) {
            return [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'details' => $this->dev ? $e->getTraceAsString() : null,
            ];
        }, $exception->getErrors()), $exception->getCode());
        $event->setResponse($response);
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        if (!($event->getResponse() instanceof JsonResponse)) {
            return;
        }

        /** @var JsonResponse $jsonResponse */
        $jsonResponse = $event->getResponse();

        $data = [
            'ok' => strpos(((string) $jsonResponse->getStatusCode()), '2') === 0,
        ];
        $data[$data['ok'] ? 'data' : 'errors'] = json_decode($jsonResponse->getContent(), true);
        $response = new JsonResponse($data, $jsonResponse->getStatusCode());
        $event->setResponse($response);
    }
}

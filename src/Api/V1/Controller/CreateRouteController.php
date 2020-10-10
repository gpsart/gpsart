<?php

declare(strict_types=1);

namespace App\Api\V1\Controller;

use App\Api\Service\RequestHandler\GpxFileRequestHandler;
use App\Api\Service\RouteCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateRouteController extends AbstractController
{
    /** @var GpxFileRequestHandler */
    private $createRouteRequestHandler;

    /** @var RouteCreator */
    private $routeCreator;

    public function __construct(
        GpxFileRequestHandler $createRouteRequestHandler,
        RouteCreator $routeCreator
    ) {
        $this->createRouteRequestHandler = $createRouteRequestHandler;
        $this->routeCreator              = $routeCreator;
    }

    public function create(Request $request): Response
    {
        if ($request->getMethod() === 'OPTIONS') {
            $response = new Response();
        } else {
            $gpxFileRequest = $this->createRouteRequestHandler->handle($request);
            $route          = $this->routeCreator->create($gpxFileRequest);

            $response = new JsonResponse(json_encode($route), 201, [], true);
        }

        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'OPTIONS, GET, POST');
        $response->headers->set('Allow', 'OPTIONS, POST');

        return $response;
    }
}
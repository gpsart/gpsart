<?php

declare(strict_types=1);

namespace App\Api\V1\Controller;

use App\Api\Repository\RouteRepository;
use App\Api\Service\ActivityCreator;
use App\Api\Service\RequestHandler\GpxFileRequestHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateActivityController extends AbstractController
{
    /** @var GpxFileRequestHandler */
    private $createRouteRequestHandler;

    /** @var ActivityCreator */
    private $activityCreator;

    /** @var RouteRepository */
    private $repository;

    public function __construct(
        GpxFileRequestHandler $createRouteRequestHandler,
        ActivityCreator $activityCreator,
        RouteRepository $repository
    ) {
        $this->createRouteRequestHandler = $createRouteRequestHandler;
        $this->activityCreator           = $activityCreator;
        $this->repository                = $repository;
    }

    public function create(string $routeId, Request $request): Response
    {
        if ($request->getMethod() === 'OPTIONS') {
            $response = new Response();
        } else {
            $route          = $this->repository->findOneById($routeId);
            $gpxFileRequest = $this->createRouteRequestHandler->handle($request);
            $activity       = $this->activityCreator->create($route, $gpxFileRequest);

            $response = new JsonResponse(json_encode($activity), 201, [], true);
        }

        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'OPTIONS, GET, POST');
        $response->headers->set('Allow', 'OPTIONS, POST');

        return $response;
    }
}
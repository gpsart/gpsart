<?php

declare(strict_types=1);

namespace App\Api\V2\Controller;

use App\Api\Repository\RouteRepository;
use Doctrine\ORM\NoResultException;
use SimpleXMLElement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Uid\Uuid;

class RouteController extends AbstractController
{
    /** @var RouteRepository */
    private $repository;

    public function __construct(RouteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function routes(): Response
    {
        $response = new JsonResponse(
            json_encode($this->repository->findAllForGallery()),
            200,
            [],
            true
        );
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    public function route(string $id): Response
    {
        try {
            $response = new JsonResponse(
                json_encode($this->repository->getOneByIdWithLeaderboard($id)),
                200,
                [],
                true
            );
            $response->headers->set('Access-Control-Allow-Origin', '*');

            return $response;
        } catch (NoResultException $exception) {
            return new Response(null, 404);
        }
    }

    public function content(string $id): Response
    {
        try {
            $route    = $this->repository->getOneByIdWithContent($id);
            $fileName = sprintf('/tmp/%s_%s.gsp', $route->getName(), Uuid::v4()->toBase32());
            $xmlSaved = (new SimpleXMLElement($route->getContent()))->asXML($fileName);

            return $this->file($fileName, $route->getName() . '.gsp', ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        } catch (NoResultException $exception) {
            return new Response(null, 404);
        }
    }
}

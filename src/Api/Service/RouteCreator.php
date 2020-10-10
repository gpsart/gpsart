<?php

declare(strict_types=1);

namespace App\Api\Service;

use App\Api\Entity\GpxFileRequest;
use App\Api\Entity\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class RouteCreator
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var RouteToActivityConverter */
    private $routeToActivityConverter;

    /** @var ActivityFileParser */
    private $activityFileParser;

    /** @var GpxPreviewImageService */
    private $previewImageService;

    public function __construct(
        EntityManagerInterface $entityManager,
        RouteToActivityConverter $routeToActivityConverter,
        ActivityFileParser $activityFileParser,
        GpxPreviewImageService $previewImageService
    ) {
        $this->entityManager            = $entityManager;
        $this->routeToActivityConverter = $routeToActivityConverter;
        $this->activityFileParser       = $activityFileParser;
        $this->previewImageService      = $previewImageService;
    }

    public function create(GpxFileRequest $request): Route
    {
        $xmlContent      = file_get_contents($request->getFilename());
        $previewImageUrl = json_decode($this->previewImageService->getImageUrl($xmlContent), true)['image'];

        $this->routeToActivityConverter->convert($request);
        $activityData = $this->activityFileParser->parse($request->getFilename());
        $xmlContent   = file_get_contents($request->getFilename());

        $route = new Route(
            Uuid::v4()->toRfc4122(),
            $request->getName(),
            (int)floor($activityData->getTotalDistance()),
            $xmlContent,
            random_int(38, 50) / 10, //todo implement rating,
            $previewImageUrl
        );

        $this->entityManager->persist($route);
        $this->entityManager->flush();

        return $route;
    }
}
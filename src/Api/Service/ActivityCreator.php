<?php

declare(strict_types=1);

namespace App\Api\Service;

use App\Api\Entity\Activity;
use App\Api\Entity\GpxFileRequest;
use App\Api\Entity\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class ActivityCreator
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ActivityFileParser */
    private $activityFileParser;

    /** @var GpxPreviewImageService */
    private $previewImageService;

    public function __construct(
        EntityManagerInterface $entityManager,
        ActivityFileParser $activityFileParser,
        GpxPreviewImageService $previewImageService
    ) {
        $this->entityManager       = $entityManager;
        $this->activityFileParser  = $activityFileParser;
        $this->previewImageService = $previewImageService;
    }

    public function create(Route $route, GpxFileRequest $activityRequest): Activity
    {
        $activityData    = $this->activityFileParser->parse($activityRequest->getFilename());
        $xmlContent      = file_get_contents($activityRequest->getFilename());
        $previewImageUrl = json_decode($this->previewImageService->getImageUrl($xmlContent), true)['image'];

        $activity = new Activity(
            Uuid::v4()->toRfc4122(),
            $route,
            $activityData->getTotalDuration(),
            $activityRequest->getName(),
            $previewImageUrl
        );

        $this->entityManager->persist($activity);
        $this->entityManager->flush();

        return $activity;
    }
}
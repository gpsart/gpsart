<?php

declare(strict_types=1);

namespace App\Api\Service\RequestHandler;

use App\Api\Entity\GpxFileRequest;
use App\Api\Type\GpxFileType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class GpxFileRequestHandler
{
    /** @var FormFactoryInterface */
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function handle(Request $request): GpxFileRequest
    {
        $gpxFileRequest = new GpxFileRequest();

        $form = $this->formFactory->create(GpxFileType::class, $gpxFileRequest);
        $form->handleRequest($request);

        /** @var UploadedFile $file */
        $file = $form->get('file')->getData();
        $gpxFileRequest->setFilename($file->getRealPath());

        return $gpxFileRequest;
    }
}
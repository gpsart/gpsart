<?php

declare(strict_types=1);

namespace App\Api\Service;

use App\Api\Exception\FailedToCreatePreviewException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class GpxPreviewImageService
{
    /** @var string */
    private $serviceUrl;

    /** @var HttpClientInterface */
    private $client;

    public function __construct(string $serviceUrl, HttpClientInterface $client)
    {
        $this->serviceUrl = $serviceUrl;
        $this->client     = $client;
    }

    public function getImageUrl(string $xmlContent): string
    {
        try {
            $response = $this->client->request('POST', $this->serviceUrl, ['body' => $xmlContent]);
        } catch (Throwable $exception) {
            throw FailedToCreatePreviewException::becauseThereWasExceptionDuringRequest($exception);
        }

        if ($response->getStatusCode() !== 200) {
            throw FailedToCreatePreviewException::becauseResponseCodeIsNotOk($response->getStatusCode());
        }

        return $response->getContent();
    }
}
<?php

declare(strict_types=1);

namespace App\Api\Service;

use App\Api\Entity\GpxFileRequest;
use App\Api\Exception\BadGpxFileException;
use DateTime;
use SimpleXMLElement;

class RouteToActivityConverter
{
    public function convert(GpxFileRequest $request): GpxFileRequest
    {
        $data = simplexml_load_file($request->getFilename());

        if (!isset($data->trk)) {
            throw BadGpxFileException::becauseThereIsNoActivityNode();
        }

        $activityNode = $data->trk;

        $startTime = new DateTime();

        foreach ($activityNode->trkseg as $lapNode) {
            if (!$lapNode->trkpt) {
                // In some cases there can be an empty lap node
                continue;
            }

            foreach ($lapNode->trkpt as $point) {
                $this->addTimeToNode($point, $startTime->modify('+1 second'));
            }
        }

        $newFileName = $request->getFilename() . 'converted';
        $data->saveXML($newFileName);
        $request->setFilename($newFileName);

        return $request;
    }

    private function addTimeToNode(SimpleXMLElement $trkpt, DateTime $timestamp)
    {
        $trkpt->addChild('time', $timestamp->format('c'));
    }
}
<?php

declare(strict_types=1);

namespace App\Api\Entity;

use App\Api\Repository\RouteRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=RouteRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Route implements JsonSerializable
{
    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $distance;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $previewUrl;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Api\Entity\Activity", mappedBy="route")
     */
    private $activities;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $rate;

    public function __construct(string $id, string $name, int $distance, string $content, float $rate, string $preview)
    {
        $this->id         = $id;
        $this->name       = $name;
        $this->content    = $content;
        $this->distance   = $distance;
        $this->rate       = $rate;
        $this->activities = new ArrayCollection();
        $this->previewUrl = $preview;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new DateTime();
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function jsonSerialize(): array
    {
        $view = [
            'id'       => $this->id,
            'name'     => $this->name,
            'rate'     => $this->rate,
            'distance' => $this->distance . ' meters',
            'image'    => $this->previewUrl,
        ];

        if ($this->activities !== null) {
            $activities = $this->activities->toArray();
            usort(
                $activities,
                function (Activity $activity1, Activity $activity2) {
                    return $activity1->getResult() > $activity2->getResult();
                }
            );

            $view['leaderboard'] = $activities;
        }

        return $view;
    }
}
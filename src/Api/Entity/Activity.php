<?php

declare(strict_types=1);

namespace App\Api\Entity;

use App\Api\Repository\ActivityRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=ActivityRepository::class)
 * @ORM\Table(name="activity",indexes={@ORM\Index(name="leaderboard_idx", columns={"total_duration"})})
 * @ORM\HasLifecycleCallbacks()
 *
 */
class Activity implements JsonSerializable
{
    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    private $id;

    /**
     * @var Route
     *
     * @ORM\ManyToOne(targetEntity="App\Api\Entity\Route", inversedBy="activities")
     * @ORM\JoinColumn(name="route_id", referencedColumnName="id", nullable=false)
     */
    private $route;

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
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $totalDuration;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    public function __construct(string $id, Route $route, int $totalDuration, string $name, string $preview)
    {
        $this->id            = $id;
        $this->route         = $route;
        $this->totalDuration = $totalDuration;
        $this->previewUrl    = $preview;
        $this->name          = $name;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new DateTime();
    }

    public function getResult(): int
    {
        return $this->totalDuration;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'     => $this->id,
            'name'   => $this->name,
            'result' => sprintf(
                '%02d:%02d:%02d',
                ($this->totalDuration / 3600),
                ($this->totalDuration / 60 % 60),
                $this->totalDuration % 60
            ),
            'image'  => $this->previewUrl,
        ];
    }
}
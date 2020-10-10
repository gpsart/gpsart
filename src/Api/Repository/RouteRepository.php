<?php

declare(strict_types=1);

namespace App\Api\Repository;

use App\Api\Entity\Route;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

class RouteRepository extends ServiceEntityRepository
{
    private const LEADERBORD_SIZE = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Route::class);
    }

    public function findAllForGallery(): array //do not fetch xml content and leaderboard
    {
        return $this->createQueryBuilder('r')
            ->select('PARTIAL r.{id, name, distance, previewUrl, rate}')
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->getResult();
    }

    public function getOneByIdWithLeaderboard(string $id): Route
    {
        $qb = $this->createQueryBuilder('r');

        return $qb->select('PARTIAL r.{id, name, distance, previewUrl, rate}')
            ->addSelect('a')
            ->leftJoin('r.activities', 'a')
            ->orderBy('a.totalDuration', 'ASC')
            ->where($qb->expr()->eq('r.id', ':id'))
            ->setParameter('id', $id)
            ->setMaxResults(self::LEADERBORD_SIZE)
            ->getQuery()
            ->getSingleResult();
    }

    public function getOneByIdWithContent(string $id): Route
    {
        $qb = $this->createQueryBuilder('r');

        return $qb->select('PARTIAL r.{id, content, name}')
            ->where($qb->expr()->eq('r.id', ':id'))
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }
}
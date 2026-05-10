<?php

namespace App\Repository;

use App\Entity\Conference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ConferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conference::class);
    }

    public function findUpcoming(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.startDate', 'ASC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }
}

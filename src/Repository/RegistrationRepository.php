<?php

namespace App\Repository;

use App\Entity\Registration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RegistrationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Registration::class);
    }

    public function findForUser(int $userId): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.session', 's')
            ->addSelect('s')
            ->leftJoin('s.room', 'room')
            ->addSelect('room')
            ->leftJoin('s.speakers', 'sp')
            ->addSelect('sp')
            ->where('r.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('s.startTime', 'ASC')
            ->getQuery()
            ->getResult();
    }
}

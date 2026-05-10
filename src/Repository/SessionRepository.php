<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    public function findByConferenceGroupedByDay(int $conferenceId): array
    {
        $sessions = $this->createQueryBuilder('s')
            ->leftJoin('s.conference', 'c')
            ->addSelect('c')
            ->leftJoin('s.room', 'r')
            ->addSelect('r')
            ->leftJoin('s.speakers', 'sp')
            ->addSelect('sp')
            ->where('c.id = :conferenceId')
            ->setParameter('conferenceId', $conferenceId)
            ->orderBy('s.startTime', 'ASC')
            ->getQuery()
            ->getResult();

        $grouped = [];
        foreach ($sessions as $session) {
            $key = $session->getStartTime()->format('Y-m-d');
            $grouped[$key][] = $session;
        }

        return $grouped;
    }

    public function hasConflict(Session $session): bool
    {
        $qb = $this->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->where('s.room = :room')
            ->andWhere(':startTime < s.endTime')
            ->andWhere(':endTime > s.startTime')
            ->setParameter('room', $session->getRoom())
            ->setParameter('startTime', $session->getStartTime())
            ->setParameter('endTime', $session->getEndTime());

        if ($session->getId()) {
            $qb->andWhere('s.id != :currentId')
                ->setParameter('currentId', $session->getId());
        }

        if ((int) $qb->getQuery()->getSingleScalarResult() > 0) {
            return true;
        }

        if ($session->getSpeakers()->isEmpty()) {
            return false;
        }

        $speakerConflictQb = $this->createQueryBuilder('s')
            ->select('COUNT(DISTINCT s.id)')
            ->join('s.speakers', 'sp')
            ->where('sp IN (:speakers)')
            ->andWhere(':startTime < s.endTime')
            ->andWhere(':endTime > s.startTime')
            ->setParameter('speakers', $session->getSpeakers())
            ->setParameter('startTime', $session->getStartTime())
            ->setParameter('endTime', $session->getEndTime());

        if ($session->getId()) {
            $speakerConflictQb->andWhere('s.id != :currentId')
                ->setParameter('currentId', $session->getId());
        }

        return (int) $speakerConflictQb->getQuery()->getSingleScalarResult() > 0;
    }
}

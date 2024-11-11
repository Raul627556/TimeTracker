<?php
namespace App\Repository;

use App\Entity\Task;
use DateTime;
use DateTimeZone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     *
     * @return Task[]
     * @throws \DateMalformedStringException
     */
    public function findTasksForToday(): array
    {
        $timezone = new DateTimeZone('Europe/Madrid');

        $startOfDay = new DateTime('now');
        $startOfDay->setTimezone($timezone);
        $startOfDay->setTime(0, 0, 0);
        $startOfDay->format('d/m/Y H:i:s');


        $endOfDay = new DateTime();
        $endOfDay->setTime(23, 59, 59);

        $queryBuilder = $this->createQueryBuilder('t')
            ->where('t.startTime >= :startOfDay')
            ->andWhere('t.startTime <= :endOfDay')
            ->setParameter('startOfDay', $startOfDay)
            ->setParameter('endOfDay', $endOfDay)
            ->orderBy('t.startTime', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     *
     * @return Task[]
     */
    public function findActiveTasks(): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.endTime IS NULL')
            ->orderBy('t.startTime', 'ASC')
            ->getQuery()
            ->getResult();
    }
}

<?php

namespace App\Repository;

use App\Entity\Messages;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Messages>
 */
class MessagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Messages::class);
    }

    /**
     * @return Messages[] Returns an array of Messages objects
     */
    public function findAllMessages(): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.userTo IS NULL')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Messages[] Returns an array of Messages objects
     */
    public function findByMessagesUserTo(): array
    {
        return $this->createQueryBuilder('m')
            ->select('m.id, IDENTITY(m.userFrom) as userFrom, IDENTITY(m.userTo) as userTo, m.content, m.dateEnvoi, u1.username as destinataire, u2.username as expediteur')
            ->innerJoin(User::class, 'u1', 'WITH', 'm.userFrom = u1.id')
            ->innerJoin(User::class, 'u2', 'WITH', 'm.userTo = u2.id')
            ->groupBy('m.id, m.userFrom, m.userTo, m.content, m.dateEnvoi, u1.username, u2.username')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Messages[] Returns an array of Messages objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Messages
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

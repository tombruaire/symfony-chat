<?php

namespace App\Repository;

use App\Entity\Amis;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Amis>
 */
class AmisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Amis::class);
    }

    public function findByCheckStatut($idDemandeur, $idCible): array
    {
        return $this->createQueryBuilder('a')
            ->select('a.demande')
            ->where('a.demandeur = :idDemandeur')
            ->andWhere('a.cible = :idCible')
            ->setParameter('idDemandeur', $idDemandeur)
            ->setParameter('idCible', $idCible)
            ->getQuery()
            ->getResult();
    }

    public function annulerDemandeAmi($idDemandeur, $idCible): void
    {
        $this->createQueryBuilder('a')
            ->delete(Amis::class, 'a')
            ->where('a.demandeur = :idDemandeur')
            ->andWhere('a.cible = :idCible')
            ->setParameter('idDemandeur', $idDemandeur)
            ->setParameter('idCible', $idCible)
            ->getQuery()
            ->execute();
    }

    public function countDemandeAmi($idCible): int
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.cible = :idCible')
            ->setParameter('idCible', $idCible)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findDemandesCible($idCible): array
    {
        return $this->createQueryBuilder('a')
            ->select('a.id, u1.username as demandeur, u2.username as cible, a.cible')
            ->innerJoin(User::class, 'u1', 'WITH', 'u1.id = a.demandeur')
            ->innerJoin(User::class, 'u2', 'WITH', 'u2.id = a.cible')
            ->where('a.cible = :idCible')
            ->groupBy('a.id, u1.username, u2.username, a.cible')
            ->setParameter('idCible', $idCible)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Amis[] Returns an array of Amis objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Amis
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

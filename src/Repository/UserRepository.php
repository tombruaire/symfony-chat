<?php

namespace App\Repository;

use AllowDynamicProperties;
use App\Entity\Amis;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
#[AllowDynamicProperties] class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $entityManager,
    )
    {
        $this->entityManager = $entityManager;
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function checkUsername($username): int
    {
        return $this->createQueryBuilder('u')
            ->select("count(u.username)")
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getResult()[0][1];
    }

    public function checkEmail($email): int
    {
        return $this->createQueryBuilder('u')
            ->select("count(u.email)")
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getResult()[0][1];
    }

    public function resetPassword($email, $password, UserPasswordHasherInterface $userPasswordHasher): void
    {
        // Recherche de l'utilisateur par son email
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        // Si l'adresse email de l'utilisateur existe
        if ($user) {
            // Mise à jour de son mot de passe en la hachant
            $user->setPassword($userPasswordHasher->hashPassword($user, $password));
            // Mise à jour du statut de l'utilisateur (online)
            $user->setOnline(false);
            // Enregistrement des changements dans la base de données
            $this->entityManager->flush();
        }
    }

    public function getToken($email): array
    {
        return $this->createQueryBuilder('u')
            ->select('u.token')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getResult()[0];
    }

    public function getUsername($email): array
    {
        return $this->createQueryBuilder('u')
            ->select('u.username')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getResult()[0];
    }

    /**
     * @param $username
     * @return User|null Returns an array of User objects
     */
    public function findUserTo($username): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult(); // Utilisation de "getOneOrNullResult" pour obtenir un résultat ou null
    }

    public function activer2FA($user): void
    {
        $user->setTwofa(true);
        $this->getEntityManager()->flush();
    }

    public function desactiver2FA($user): void
    {
        $user->setTwofa(false);
        $this->getEntityManager()->flush();
    }

    public function findCibleId(): array
    {
        return $this->createQueryBuilder('u')
            ->select('u.id, u.username, a1.demandeur as demandeur_id, a.cible, a.demande')
            ->innerJoin(Amis::class, 'a1', 'WITH', 'u.id = a.cible')
            ->groupBy('u.id, u.username, a.demandeur, a.cible, a.demande')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

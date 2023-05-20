<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Session>
 *
 * @method Session|null find($id, $lockMode = null, $lockVersion = null)
 * @method Session|null findOneBy(array $criteria, array $orderBy = null)
 * @method Session[]    findAll()
 * @method Session[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    public function save(Session $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Session $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    

    public function findStagiairesNotInSession(int $sessionId)
    {
       
        $entityManager = $this->getEntityManager();

        $subQuery = $entityManager->createQueryBuilder();

        $subQuery->select('st.id')
                ->from('App\Entity\stagiaire', 'st')
                ->join('st.sessions', 's')
                ->where('s.id = :id')
                ->setParameter('id', $sessionId);

        $qb = $entityManager->createQueryBuilder();

        $qb->select('sta')
        ->from('App\Entity\stagiaire', 'sta')
        ->where($qb->expr()->notIn('sta.id', $subQuery->getDQL()))
        ->orderBy('sta.nom', 'ASC')
        ->setParameter('id', $sessionId);

        return $qb->getQuery()->getResult();
        
    }


//    /**
//     * @return Session[] Returns an array of Session objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Session
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

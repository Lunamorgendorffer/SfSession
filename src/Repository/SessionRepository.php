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
    // Récupérer l'EntityManager
    $entityManager = $this->getEntityManager();

    // Créer une sous-requête pour sélectionner les IDs des stagiaires présents dans la session donnée
    $subQuery = $entityManager->createQueryBuilder();
    $subQuery->select('st.id') //Sélectionne l'attribut 'id' de l'entité "stagiaire" pour la sous-requête.
        ->from('App\Entity\stagiaire', 'st') //Spécifie que la requête se fait à partir de l'entité "stagiaire" et utilise l'alias 'st' pour cette entité dans la requête.
        ->join('st.sessions', 's') // Effectue une jointure avec la relation "sessions" de l'entité "stagiaire" en utilisant l'alias 's' pour cette relation dans la requête.
        ->where('s.id = :id') //Ajoute une condition pour filtrer les résultats de la requête, où l'ID de la session doit correspondre à la valeur du paramètre ':id'.
        ->setParameter('id', $sessionId); // Définit la valeur du paramètre ':id' avec la valeur de la variable $sessionId.

    // Créer une requête principale pour sélectionner les stagiaires qui ne sont pas dans la session donnée
    $qb = $entityManager->createQueryBuilder();
    $qb->select('sta') //Sélectionne l'entité "stagiaire" pour la requête principale.
        ->from('App\Entity\stagiaire', 'sta') // Spécifie que la requête principale se fait à partir de l'entité "stagiaire" et utilise l'alias 'sta' pour cette entité dans la requête.
        ->where($qb->expr()->notIn('sta.id', $subQuery->getDQL())) // Ajoute une condition pour filtrer les résultats de la requête principale, où l'ID du stagiaire ne doit pas être dans la sous-requête.
        ->orderBy('sta.nom', 'ASC') //Trie les résultats de la requête par le nom du stagiaire en ordre croissant.
        ->setParameter('id', $sessionId); //Définit la valeur du paramètre ":id" avec la valeur de la variable $sessionId.

    // Exécuter la requête et retourner les résultats
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

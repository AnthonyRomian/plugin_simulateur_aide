<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Resultat;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    /**
    * @return Utilisateur[] Returns an array of Utilisateur objects
    */

    public function findByExampleField($dateSimulation)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.date_simulation = :val')
            ->setParameter('val', $dateSimulation)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findSearch(SearchData $search): array
    {
        $queryBuilder = $this
            //récupère les utilisateurs
            ->createQueryBuilder('u')
            //sélectionne toutes les infos liées aux utilisateurs et resultats
            ->select('r', 'u')
            //liaison campus / sorties
            ->join('u.resultat', 'r');

        //recherche nom de utilisateur contient
        if (!empty($search->q)) {
            $queryBuilder = $queryBuilder
                ->andWhere('u.nom LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        //recherche departement
        if (!empty($search->departement)) {
            $queryBuilder = $queryBuilder
                ->andWhere('u.code_postal LIKE :departement')
                ->setParameter('departement', "%{$search->departement}%");
        }

        //recherche checkbox
        if (!empty($search->eauChaudeSanitaire)) {
            $queryBuilder = $queryBuilder
                ->andWhere('u.produit_vise = :EauChaudeSanitaire')
                ->setParameter('EauChaudeSanitaire', 'Eau chaude sanitaire');
        }

        if (!empty($search->eauChaudeSanitaireChauffage)) {
            $queryBuilder = $queryBuilder
                ->andWhere('u.produit_vise = :eauChaudeSanitaireChauffage')
                ->setParameter('eauChaudeSanitaireChauffage', 'Eau chaude sanitaire et chauffage');
        }

        //recherche par energie
        if (!empty($search->energie)) {
            $queryBuilder = $queryBuilder
                ->andWhere('u.energie IN (:energie)')
                ->setParameter('energie', $search->energie);
        }
        return $queryBuilder->getQuery()->getResult();
    }
    /*
    public function findOneBySomeField($value): ?Utilisateur
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

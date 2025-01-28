<?php

namespace App\Repository;

use App\Entity\Oeuvre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Oeuvre>
 */
class OeuvreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Oeuvre::class);
    }
    public function findByArtisteName(string $name): array
{
    return $this->createQueryBuilder('o')
        ->join('o.artiste', 'a') // Jointure avec l'entité Artiste
        ->where('a.nom LIKE :name') // Filtre selon le nom de l'artiste
        ->setParameter('name', '%' . $name . '%') // Permet la recherche partielle
        ->getQuery()
        ->getResult();
}
public function findTopFollowedArtists(int $limit = 5): array
{
    return $this->createQueryBuilder('a')
        ->select('a, COUNT(v.id) AS followerCount')
        ->leftJoin('a.visiteurs', 'v')
        ->groupBy('a.id')
        ->orderBy('followerCount', 'DESC')
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();
}
public function countOeuvresByArtist(): array
    {
        return $this->createQueryBuilder('o')
            ->select('a.nom AS artiste_nom, COUNT(o.id) AS nb_oeuvres')
            ->join('o.artiste', 'a')
            ->groupBy('a.id')
            ->getQuery()
            ->getResult();
    }
     // Cette méthode permet d'obtenir une requête paginable
     public function findAllQuery(): QueryBuilder
     {
         return $this->createQueryBuilder('v')
             ->orderBy('v.id', 'ASC');
     }
 






//    /**
//     * @return Oeuvre[] Returns an array of Oeuvre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Oeuvre
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

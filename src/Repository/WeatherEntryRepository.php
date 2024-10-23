<?php

namespace App\Repository;

use App\Entity\WeatherEntry;
use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WeatherEntry>
 *
 * @method WeatherEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method WeatherEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method WeatherEntry[]    findAll()
 * @method WeatherEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeatherEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WeatherEntry::class);
    }
    public function findByLocation(Location $location)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->where('m.location = :location')
            ->setParameter('location', $location)
            ->andWhere('m.date > :now')
            ->setParameter('now', date('Y-m-d'));
    
        $query = $qb->getQuery();
        $result = $query->getResult();
        return $result;
    }

//    /**
//     * @return WeatherEntry[] Returns an array of WeatherEntry objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?WeatherEntry
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

<?php

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\Truck;
use DateInterval;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Reservation $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Reservation $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Reservation[] Returns an array of Reservation objects
     */
    public function findByTruckInTheSameWeek(Truck $truck, DateTime $date)
    {
        $monday = new DateTime();
        $sunday = new DateTime();
        if ($date->format('w') === '1')
        {
            $monday = clone $date;
            $sunday = clone $date->add(new DateInterval('P6D'));
        }
        else if($date->format('w') === '0')
        {
            $sunday = clone $date;
            $monday = clone $date->sub(new DateInterval('P6D'));
        }
        else
        {
            $monday = clone $date->sub(new DateInterval('P' . $date->format('w') - 1 . 'D'));
            //sunday being 0, we have to pick the '0' from next week
            $sunday = clone $date->add(new DateInterval('P1W'))->sub(new DateInterval('P' . $date->format('w') . 'D'));
        }
        
        return $this->createQueryBuilder('r')
            ->join('r.truck', 't')
            ->andWhere('t.id = :truckId')
            ->andWhere('r.date BETWEEN :monday AND :sunday')
            ->setParameter('truckId', $truck->getId())
            ->setParameter('monday', $monday)
            ->setParameter('sunday', $sunday)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Reservation[] Returns an array of Reservation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reservation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

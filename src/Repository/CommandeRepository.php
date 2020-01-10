<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    /**
     * @return mixed
     */
    public function findByASC()
    {
        return $this->createQueryBuilder('c')->orderBy('c.date', 'DESC')->getQuery()->getResult();
    }

    /**
     * @param null $fseur
     * @param null $debut
     * @param null $fin
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getMontant($fseur = null, $debut = null, $fin = null)
    {
        $q = $this->createQueryBuilder('c')->select('sum(c.montant)');
        if ($fseur and  $debut and $fin){
            $q->innerJoin('c.fournisseur', 'f')
                ->where('f.id = :fseur')
                ->andWhere('c.date BETWEEN :debut AND :fin')
                ->setParameters([
                    'fseur'=>$fseur,
                    'debut'=>$debut,
                    'fin'=>$fin
                ]);
        }elseif ($debut and $fin){
            $q->where('c.date BETWEEN :debut AND :fin')
                ->setParameters([
                    'debut' => $debut,
                    'fin' => $fin
                ]);
        }else{
            $q->where('c.date BETWEEN :debut and :fin')
                ->setParameters([
                    'debut' => date('Y-m-01'),
                    'fin' => date('Y-m-31')
                ]);
        }

        return $q->getQuery()->getSingleScalarResult();
    }

    /**
     * @param null $fseur
     * @param null $debut
     * @param null $fin
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findBySearch($fseur = null, $debut = null, $fin = null)
    {
        $q = $this->createQueryBuilder('c')->orderBy('c.date','DESC');
        if ($fseur and  $debut and $fin){
            $q->innerJoin('c.fournisseur', 'f')
                ->where('f.id = :fseur')
                ->andWhere('c.date BETWEEN :debut AND :fin')
                ->setParameters([
                    'fseur'=>$fseur,
                    'debut'=>$debut,
                    'fin'=>$fin
                ]);
        }elseif ($debut and  $fin){ ;
            $q->where('c.date BETWEEN :debut AND :fin')
                ->setParameters([
                    'debut' => $debut,
                    'fin' => $fin
                ]);
        }else{ //die('a la fin');
            $q->where('c.date BETWEEN :debut and :fin')
                ->setParameters([
                    'debut' => date('Y-m-01'),
                    'fin' => date('Y-m-31')
                ]);
        }

        return $q->getQuery()->getResult();
    }

    // /**
    //  * @return Commande[] Returns an array of Commande objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Commande
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

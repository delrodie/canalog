<?php

namespace App\Repository;

use App\Entity\Operation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Operation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Operation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Operation[]    findAll()
 * @method Operation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OperationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Operation::class);
    }

    /**
     * @param $type
     * @return mixed
     */
    public function findByType($type)
    {
        return $this->createQueryBuilder('o')
                    ->innerJoin('o.type', 't')
                    ->where('t.libelle LIKE :type')
                    ->orderBy('o.date', 'DESC')
                    ->setParameter('type', '%'.$type.'%')
                    ->getQuery()->getResult()
            ;
    }

    /**
     * @param $type
     * @param null $officine
     * @param null $mode
     * @param null $debut
     * @param null $fin
     * @return mixed
     */
    public function findBySearch($type, $officine=null,$mode=null,$debut=null,$fin=null)
    {
        $q = $this->createQueryBuilder('o')->innerJoin('o.type', 't');
        // Si officine definit et mode definit alors rechercher
        if ($officine and $mode){
                $q->where('t.libelle LIKE :type')
                ->andWhere('o.officine = :officine')
                ->andWhere('o.mode = :mode')
                ->andWhere('o.date BETWEEN :debut AND :fin')
                ->orderBy('o.date', 'DESC')
                ->setParameters([
                    'type'=>'%'.$type.'%',
                    'officine'=>$officine,
                    'mode'=>$mode,
                    'debut'=>$debut,
                    'fin'=>$fin
                ]);
        }elseif($officine){
                $q->where('t.libelle LIKE :type')
                ->andWhere('o.officine = :officine')
                ->andWhere('o.date BETWEEN :debut AND :fin')
                ->orderBy('o.date', 'DESC')
                ->setParameters([
                    'type'=>'%'.$type.'%',
                    'officine'=>$officine,
                    'debut'=>$debut,
                    'fin'=>$fin
                ]);
        }elseif($mode){
                $q->where('t.libelle LIKE :type')
                ->andWhere('o.mode = :mode')
                ->andWhere('o.date BETWEEN :debut AND :fin')
                ->orderBy('o.date', 'DESC')
                ->setParameters([
                    'type'=>'%'.$type.'%',
                    'mode'=>$mode,
                    'debut'=>$debut,
                    'fin'=>$fin
                ]);
        }elseif($debut and $fin){
                $q->where('t.libelle LIKE :type')
                ->andWhere('o.date BETWEEN :debut AND :fin')
                ->orderBy('o.date', 'DESC')
                ->setParameters([
                    'type'=>'%'.$type.'%',
                    'debut'=>$debut,
                    'fin'=>$fin
                ]);
        }else{
            $q->where('t.libelle LIKE :type')
                ->andWhere('o.date BETWEEN :debut AND :fin')
                ->orderBy('o.date', 'DESC')
                ->setParameters([
                    'type' => '%'.$type.'%',
                    'debut' => date('Y-m-01'),
                    'fin' => date('Y-m-31')
                ])
                ;
        }

        return $q->getQuery()->getResult();
    }

    public function getMontantBySearch($type, $officine=null,$mode=null,$debut=null,$fin=null)
    {
        $q = $this->createQueryBuilder('o')->select('sum(o.montant)')->innerJoin('o.type', 't');
        // Si officine definit et mode definit alors rechercher
        if ($officine and $mode){
                $q->where('t.libelle LIKE :type')
                ->andWhere('o.officine = :officine')
                ->andWhere('o.mode = :mode')
                ->andWhere('o.date BETWEEN :debut AND :fin')
                ->setParameters([
                    'type'=>'%'.$type.'%',
                    'officine'=>$officine,
                    'mode'=>$mode,
                    'debut'=>$debut,
                    'fin'=>$fin
                ]);
        }elseif($officine){
                $q->where('t.libelle LIKE :type')
                ->andWhere('o.officine = :officine')
                ->andWhere('o.date BETWEEN :debut AND :fin')
                ->setParameters([
                    'type'=>'%'.$type.'%',
                    'officine'=>$officine,
                    'debut'=>$debut,
                    'fin'=>$fin
                ]);
        }elseif($mode){
                $q->where('t.libelle LIKE :type')
                ->andWhere('o.mode = :mode')
                ->andWhere('o.date BETWEEN :debut AND :fin')
                ->setParameters([
                    'type'=>'%'.$type.'%',
                    'mode'=>$mode,
                    'debut'=>$debut,
                    'fin'=>$fin
                ]);
        }elseif($debut and $fin){
                $q->where('t.libelle LIKE :type')
                ->andWhere('o.date BETWEEN :debut AND :fin')
                ->setParameters([
                    'type'=>'%'.$type.'%',
                    'debut'=>$debut,
                    'fin'=>$fin
                ]);
        }else{
            $q->where('t.libelle LIKE :type')
                ->andWhere('o.date BETWEEN :debut AND :fin')
                ->setParameters([
                    'type' => '%'.$type.'%',
                    'debut' => date('Y-m-01'),
                    'fin' => date('Y-m-31')
                ])
                ;
        }

        return $q->getQuery()->getSingleScalarResult();
    }

    public function getMontantByType($type)
    {
        return $this->createQueryBuilder('o')
                    ->select('sum(o.montant)')
                    ->innerJoin('o.type', 't')
                    ->where('t.libelle LIKE :type')
                    ->andWhere('o.date BETWEEN :debut AND :fin')
                    ->orderBy('o.date', 'DESC')
                    ->setParameters([
                        'type' => '%'.$type.'%',
                        'debut' => date('Y-m-01'),
                        'fin' => date('Y-m-31')
                    ])
                    ->getQuery()->getSingleScalarResult()
            ;
    }

    /**
     * @param null $officine
     * @param null $debut
     * @param null $fin
     * @return mixed
     */
    public function findJournal($officine=null,$debut=null,$fin=null)
    {

        if ($officine and $debut and $fin){
            $q = $this->createQueryBuilder('o')
                        ->where('o.officine = :officine')
                        ->andWhere('o.date BETWEEN :debut AND :fin')
                        ->orderBy('o.date','ASC')
                        ->setParameters([
                            'officine'=>$officine,
                            'debut'=>$debut,
                            'fin'=>$fin
                        ])
                ;
        }elseif($debut and $fin){
            $q = $this->createQueryBuilder('o')
                    ->where('o.date BETWEEN :debut AND :fin')
                    ->setParameters([
                        'debut'=>$debut,
                        'fin'=>$fin
                    ])
                ;
        }else{
            $q = $this->createQueryBuilder('o')
                    ->where('o.date BETWEEN :debut AND :fin')
                    ->orderBy('o.date','ASC')
                    ->setParameters([
                        'debut'=>date('Y-m-01'),
                        'fin'=>date('Y-m-31')
                    ])
                ;
        }

        return $q->getQuery()->getResult();
    }

    // /**
    //  * @return Operation[] Returns an array of Operation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Operation
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

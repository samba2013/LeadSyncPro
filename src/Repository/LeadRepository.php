<?php

namespace App\Repository;

use App\Entity\Lead;
use App\Repository\Interfaces\LeadRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<Lead>
 */
class LeadRepository extends ServiceEntityRepository implements LeadRepositoryInterface
{
    public function __construct(private readonly LoggerInterface $logger, ManagerRegistry $registry)
    {
        parent::__construct($registry, Lead::class);
    }

    //    /**
    //     * @return Lead[] Returns an array of Lead objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Lead
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    function saveChanges():bool
    {
        try {
            $this->getEntityManager()->flush();
        } catch (\Throwable $th) {
            $this->logger->error((string)$th);
            return false;
        }
        return true;
    }

    function addLead(Lead $lead):bool
    {
        try {
            if(!$this->leadExists($lead->getEmail())){
                $this->getEntityManager()->persist($lead);
            }
        } catch (\Throwable $th) {
             $this->logger->error((string)$th);
             return false;
        }
        return true;

    }
    function leadExists(string $email):bool
    {
        return $this->findOneBy(['email'=>$email]) !== null;
    }
    function deleteLead(Lead $lead):bool
    {
        try {
           $this->getEntityManager()->remove($lead);
        } catch (\Throwable $th) {
             $this->logger->error((string)$th);
            return false;
        }
        return true;
    }
    function getLeads():array
    {
        return $this->findAll();
    }
    function findLeadById(int $id):?Lead
    {
        return $this->find($id);
    }
}

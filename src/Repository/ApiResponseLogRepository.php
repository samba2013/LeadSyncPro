<?php

namespace App\Repository;

use App\Entity\ApiResponseLog;
use App\Repository\Interfaces\ApiResponseLogRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ApiResponseLog>
 */
class ApiResponseLogRepository extends ServiceEntityRepository implements ApiResponseLogRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiResponseLog::class);
    }

    //    /**
    //     * @return ApiResponseLog[] Returns an array of ApiResponseLog objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ApiResponseLog
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findApiResponseLogById(int $id) :?ApiResponseLog{
        return $this->find($id);
    }

    function saveChanges():bool{
        try {
            $this->getEntityManager()->flush();
        } catch (\Throwable $ex) {
            $this->logger->error((string)$ex);
            return false;
        }
        return true;
    }

    public function addApiResponseLog(ApiResponseLog $apiRes):void
    {
        $this->getEntityManager()->persist($apiRes);
    }

    function removeApiResponseLog(ApiResponseLog $apiRes):void
    {
        $this->getEntityManager()->remove($apiRes);
    }
}

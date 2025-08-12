<?php

namespace App\Repository;

use App\Entity\ApiRequestLog;
use App\Repository\Interfaces\ApiRequestLogRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<ApiRequestLog>
 */
class ApiRequestLogRepository extends ServiceEntityRepository implements ApiRequestLogRepositoryInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        ManagerRegistry $registry)
    {
        parent::__construct(
            $registry, ApiRequestLog::class);
    }

    //    /**
    //     * @return ApiRequestLog[] Returns an array of ApiRequestLog objects
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

    //    public function findOneBySomeField($value): ?ApiRequestLog
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findApiRequestLogById(int $id) :?ApiRequestLog{
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

    public function addApiRequestLog(ApiRequestLog $apiReq):void
    {
        $this->getEntityManager()->persist($apiReq);
    }

    function removeApiRequestLog(ApiRequestLog $apiReq):void
    {
        $this->getEntityManager()->remove($apiReq);
    }
}

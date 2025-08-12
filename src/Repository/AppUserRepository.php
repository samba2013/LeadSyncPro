<?php

namespace App\Repository;

use App\Entity\AppUser;
use App\Model\AppUserUpdateDto;
use App\Repository\Interfaces\AppUserRepositoryInterface;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * @extends ServiceEntityRepository<AppUser>
 */
class AppUserRepository extends ServiceEntityRepository implements AppUserRepositoryInterface
{
    public function __construct(
        private readonly LoggerInterface $logger, 
        ManagerRegistry $registry)
    {
        parent::__construct($registry, AppUser::class);
    }

    //    /**
    //     * @return AppUser[] Returns an array of AppUser objects
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

    //    public function findOneBySomeField($value): ?AppUser
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findAppUserByApiKey(string $apiKeyToken):?AppUser
    {
        throw new \Exception("Implementation missing");
    }

    public function getAppUsers():array
    {
        return $this->createQueryBuilder('p')
                ->where("p.deleted_at is null")
                ->leftJoin('p.api_tokens', 'c')
                ->addSelect('c') // Select the child entities
                ->getQuery()
                ->getArrayResult();
    }

    public function saveChanges():bool
    {
        try {
            $this->getEntityManager()->flush();
        } catch (\Throwable $th) {
            $this->logger->error((string)$th);
            return false;
        }
        return true;
    }

    public function addAppUser(AppUser $user):bool
    {
        try {
            $this->getEntityManager()->persist($user);
        } catch (\Throwable $th) {
            $this->logger->error((string)$th);
            return false;
        }
        return true;
    }

    public function updateAppUser(AppUser &$user, AppUserUpdateDto $dto){

       // $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $updated=false;
        foreach (get_object_vars($dto) as $property => $value) {
            // if (!$propertyAccessor->isWritable($user, $property)) {
            //     throw \Exception($user::class." don)
            // }
            //$propertyAccessor->setValue($user, $property, $value);
            if($value !== null){
                call_user_func([$user,"set".ucfirst($property)],$value);
                $updated = true;
            }
            
        }
        if($updated) $user->setUpdatedAt(new DateTimeImmutable());

    

    }

     public function deleteAppUser(AppUser $user):bool
    {
        try {
            $user->setDeletedAt(new DateTimeImmutable());
        } catch (\Throwable $th) {
            $this->logger->error((string)$th);
            return false;
        }
        return true;
    }



    public function findAppUserByEmail(string $email): ?AppUser
    {
        return $this->findOneBy(['email'=>$email,'deletedAt'=>null]);
    }

    public function findAppUserById(int $id,bool $includeDeleted = false): ?AppUser
    {
        if(!$includeDeleted)
            return $this->findOneBy(['id'=>$id,'deleted_at'=>null]);
        return $this->find($id);
    }

    public function findAllWithoutApiTokens(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT u
            FROM App\Entity\AppUser u WHERE u.deleted_at is null'
        );

        return $query->getArrayResult();
    }

    public function refreshAppUser(AppUser &$user){
        $this->getEntityManager()->refresh($user);
    }
}

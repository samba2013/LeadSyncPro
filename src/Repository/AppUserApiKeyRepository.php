<?php

namespace App\Repository;

use App\Entity\AppUserApiKey;
use App\Repository\Interfaces\AppUserApiKeyRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<AppUserApiKey>
 */
class AppUserApiKeyRepository extends ServiceEntityRepository implements AppUserApiKeyRepositoryInterface
{
    public function __construct(private readonly LoggerInterface $logger, ManagerRegistry $registry)
    {
        parent::__construct($registry, AppUserApiKey::class);
    }

    public function findUserApiKeyByKey(string $apiKey) : ?AppUserApiKey
    {
            return  $this->findOneBy(["api_key"=>hash("sha256",$apiKey)]);
    }     
    

    public function createUserApiKey(AppUserApiKey $apiKey) : bool
    {

        try{
            $this->getEntityManager()->persist($apiKey);
        }catch(\Exception $e){
            $this->logger->error("Error occured while saving new object of ".AppUserApiKey::class);
            return false;
        }

        return true;
    }

    public function saveChanges():bool
    {
         try{
            $this->getEntityManager()->flush();
        }catch(\Exception $e){
            $this->logger->error((string)$e);
            return false;
        }

        return true;

    }

    public function userApiKeyExists(string $apiKey):bool{
        return $this->findUserApiKeyByKey($apiKey) !== null;
    }


    public function deleteUserApiKey(AppUserApiKey $apiKey) : bool{
        if($this->userApiKeyExists($apiKey->getApiKey()))
        {
            $this->getEntityManager()->remove($apiKey);
            return true;
        }
        return false;
    }

}

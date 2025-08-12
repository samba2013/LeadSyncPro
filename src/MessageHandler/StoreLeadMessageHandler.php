<?php
namespace App\MessageHandler;

use App\Entity\Lead;
use App\Message\StoreLeadMessage;
use App\Repository\Interfaces\ApiRequestLogRepositoryInterface;
use App\Repository\Interfaces\LeadRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class StoreLeadMessageHandler {
    public function __construct(
        private readonly LeadRepositoryInterface $leadRepo,
        private readonly ApiRequestLogRepositoryInterface $apiRequestRepo,
        private readonly LoggerInterface $logger
    )
    {
        
    }

    public function __invoke(StoreLeadMessage $lead)
    {
        try{
            $dto = $lead->getLeadCreateDto();
            $apiReqId = $lead->getLeadSourceId();
            $apiReq = is_numeric($apiReqId) ? $this->apiRequestRepo->findApiRequestLogById($apiReqId) : null;

            $newLead = Lead::fromCreateDto($dto);
            $newLead->setApiRequest($apiReq);
            $this->leadRepo->addLead($newLead);
            $this->leadRepo->saveChanges();
            
        }catch(\Exception $e){
            $this->logger->error((string)$e);
        }
        
    }
}


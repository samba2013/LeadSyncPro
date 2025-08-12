<?php


namespace App\Message;

use App\Model\LeadCreateDto;


class StoreLeadMessage {

    public function __construct(
        private LeadCreateDto $leadCreateDto,
        private ?int $apiRequestLogId
    )
    {
        
    }

    public function getLeadCreateDto():LeadCreateDto
    {
        return $this->leadCreateDto;
    }

    public function getLeadSourceId():?int
    {
        return $this->apiRequestLogId;
    }
}
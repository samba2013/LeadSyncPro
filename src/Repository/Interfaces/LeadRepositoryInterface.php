<?php

namespace App\Repository\Interfaces;

use App\Entity\Lead;

interface LeadRepositoryInterface {

    function saveChanges():bool;
    function addLead(Lead $lead):bool;
    function leadExists(string $email):bool;
    function deleteLead(Lead $lead):bool;
    function getLeads():array;
    function findLeadById(int $id):?Lead;
}
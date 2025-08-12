<?php


namespace App\Repository\Interfaces;

use App\Entity\ApiRequestLog;

interface ApiRequestLogRepositoryInterface {
    function findApiRequestLogById(int $id) :?ApiRequestLog;

    function saveChanges():bool;

    function addApiRequestLog(ApiRequestLog $apiReq):void;

    function removeApiRequestLog(ApiRequestLog $apiReq):void;

    
}
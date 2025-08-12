<?php

namespace App\Repository\Interfaces;

use App\Entity\ApiResponseLog;

interface ApiResponseLogRepositoryInterface{
    function findApiResponseLogById(int $id) :?ApiResponseLog;

    function saveChanges():bool;

    function addApiResponseLog(ApiResponseLog $apiRes):void;

    function removeApiResponseLog(ApiResponseLog $apiRes):void;
}
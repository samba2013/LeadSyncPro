<?php

namespace App\Repository\Interfaces;

use App\Entity\AppUserApiKey;

interface AppUserApiKeyRepositoryInterface{

    function findUserApiKeyByKey(string $apiKey) : ?AppUserApiKey;
    function createUserApiKey(AppUserApiKey $apiKey) : bool;
    function saveChanges():bool;
    function userApiKeyExists(string $apiKey):bool;
    function deleteUserApiKey(AppUserApiKey $apiKey):bool;
    //function findUserApiKeyByName(string $name) : ?AppUserApiKey;

}
<?php

namespace App\Repository\Interfaces;

use App\Entity\AppUser;
use App\Entity\AppUserApiKey;
use App\Model\AppUserUpdateDto;

interface AppUserRepositoryInterface {

    function findAppUserByApiKey(string $apiKeyToken):?AppUser;

    function getAppUsers():array;

    function saveChanges():bool;
    function updateAppUser(AppUser &$user, AppUserUpdateDto $dto);

    function addAppUser(AppUser $user):bool;
    function deleteAppUser(AppUser $user):bool;

    function findAppUserByEmail(string $email): ?AppUser;

    function findAppUserById(int $id): ?AppUser;
    function findAllWithoutApiTokens(): array;

    function refreshAppUser(AppUser &$user);
}
<?php

namespace App\Model;

use App\Entity\AppUser;
use DateTime;

class AppUserViewDto{

    public int $id = 0;
    public string $email = "";
    public ?string $fullName = null;
    public array $roles = [];
    public ?string $description = null;
    public ?string $sourceUrl = null;
    public ?string $createdAt = null;
    public ?string $updatedAt = null;
    public bool $isActive = true;

     static function getInstanceFromAppUser(AppUser $appUser):AppUserViewDto
    {

        $instance = new self();

        $instance->id = $appUser->getId();
        $instance->email = $appUser->getEmail();
        $instance->fullName = $appUser->getFullName();
        $instance->roles = $appUser->getRoles();
        $instance->description = $appUser->getDescription();
        $instance->sourceUrl = $appUser->getSourceUrl();
        $instance->createdAt = $appUser->getCreatedAt()->format("c");
        $instance->updatedAt = $appUser->getUpdatedAt()?->format("c");
        $instance->isActive = $appUser->isActive();
        return $instance;

    }


}
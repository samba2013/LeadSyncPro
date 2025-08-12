<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class AppUserUpdateDto
{    

    #[Assert\Length(max:255)]
    public ?string $fullName;
    public ?array $roles;
    
    #[Assert\Length(max:255)]
    public ?string $description;
    
    public ?string $sourceUrl;

    public ?bool $isActive;

}
<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class AppUserCreateDto{

    #[Assert\NotBlank]
    #[Assert\Email(
                message: 'The email {{ value }} is not a valid email.',
                mode: Assert\Email::VALIDATION_MODE_STRICT
        )]
    #[Assert\Length(max:100)]
    public string $email;
    
    #[Assert\Length(max:255)]
    public ?string $fullName = null;
    
    public array $roles = ["ROLE_API_CLIENT"];
    
    #[Assert\Length(max:255)]
    public ?string $description = null;
    
    public ?string $sourceUrl = null;


}
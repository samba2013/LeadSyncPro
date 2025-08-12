<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as AppAsserts;

class AppUserApiKeyCreateDto{

    
    #[Assert\DateTime(format: "Y-m-d H:i:s", message: "Invalid expiration date")]
    #[AppAsserts\ValidExpirationDate(mode:'strict')]
    public ?string $expirationDate = null;


}
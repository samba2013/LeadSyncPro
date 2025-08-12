<?php


namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Email;

class LeadCreateDto {


    public function __construct(
        #[Assert\Length(max:100)]
        public ?string $firstName,

        #[Assert\Length(max:100)]
        public ?string $lastName,

        #[Assert\Email(
                message: 'The email {{ value }} is not a valid email.',
                mode: Email::VALIDATION_MODE_STRICT
        )]
        #[Assert\NotBlank]
        #[Assert\Length(max:255)]
        public string $email,

        #[Assert\NotBlank]
        public array $verticals,

        #[Assert\Length(max:50)]
        public ?string $phone,

        #[Assert\Date]
        public ?string $dob,

        #[Assert\Length(max:255)]
        public ?string $city,

        #[Assert\Length(max:2)]
        public ?string $provinceCode,

        #[Assert\Length(max:2)]
        public ?string $countryCode,

        #[Assert\Length(max:20)]
        public ?string $zipCode,

        #[Assert\Type(type:'array')]
        public ?array $extraData = null

    ) {
    }

}
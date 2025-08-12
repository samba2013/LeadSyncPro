<?php

namespace App\Validator;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ValidExpirationDate extends Constraint
{
    public string $message = 'The expiration date "{{ string }}" is invalid: it need to be in the future with Y-m-d H:i:s format.';
    

    #[HasNamedArguments]
    public function __construct(
        private string $mode,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct([], $groups, $payload);
    }



    public function __sleep(): array
    {
        return array_merge(
            parent::__sleep(),
            [
                'mode'
            ]
        );
    }
}
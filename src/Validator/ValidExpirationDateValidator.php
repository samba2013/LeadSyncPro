<?php

namespace App\Validator;

use DateTimeImmutable;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ValidExpirationDateValidator extends ConstraintValidator {
    public function validate(mixed $value, Constraint $constraint): void{

        if (!$constraint instanceof ValidExpirationDate) {
            throw new UnexpectedTypeException($constraint, ValidExpirationDate::class);
        }

        if($value == null) return;
        $apiKeyExpiresAt = null;
        try {
            $apiKeyExpiresAt =  new DateTimeImmutable($value);
        } catch (\Throwable $th) {
            throw new UnexpectedValueException($value, 'datetime');
        }

        if($apiKeyExpiresAt > new \DateTimeImmutable()){
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $value)
            ->addViolation();


    }
}
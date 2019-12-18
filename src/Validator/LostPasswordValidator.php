<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

class LostPasswordValidator
{

    /**
     * @var ConstraintViolationListInterface
     */
    protected $constraints;

    /**
     * @param String $email
     * @return array
     */
    public function validateEmail(String $email): array
    {
        $validator = Validation::createValidator();

        $input = ['email' => $email];

        $constraints = new Assert\Collection([
            'email' => [
                new Assert\Email(['message' => 'lost_password.invalid.email']),
                new Assert\NotBlank(['message' => 'lost_password.blank.email'])
            ]
        ]);

        $this->constraints = $validator->validate($input, $constraints);

        return $this->getErrorMessages();
    }

    /**
     * @param String $username
     * @return array
     */
    public function validateUsername(String $username): array
    {
        $validator = Validation::createValidator();

        $input = ['username' => $username];

        $constraints = new Assert\Collection([
            'username' => [
                new Assert\Type(['type' => 'alnum', 'message' => 'lost_password.invalid.username']),
                new Assert\NotBlank(['message' => 'lost_password.blank.username'])
            ]
        ]);

        $this->constraints = $validator->validate($input, $constraints);

        return $this->getErrorMessages();
    }

    /**
     * @return array
     */
    public function getErrorMessages(): array
    {
        $errors = array();
        foreach ($this->constraints as $constraint) {
            array_push($errors, $constraint->getMessage());
        }

        return $errors;
    }
}

<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

class LostPasswordValidation
{

    /**
     * @var array
     */
    protected $constraints = [];

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

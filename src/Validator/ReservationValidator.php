<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

class ReservationValidator
{

    /**
     * @var ConstraintViolationListInterface
     */
    protected $constraints;

    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function validateInput(Request $request): array
    {
        $validator = Validation::createValidator();

        $input = [
            'email' => $request->get('email'),
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'contractor' => $request->get('contractor'),
            'visitDate' => $request->get('visitDate'),
        ];

        $constraint = new Assert\Collection([
            'firstname' => new Assert\Length([
                'min' => 2,
                'minMessage' => 'firstname.short',
                'max' => 32,
                'maxMessage' => 'firstname.long',
            ]),
            'lastname' => new Assert\Length([
                'min' => 2,
                'minMessage' => 'lastname.short',
                'max' => 32,
                'maxMessage' => 'lastname.long',
            ]),
            'email' => [
                new Assert\Email(['message' => 'email.invalid']),
                new Assert\NotBlank(['message' => 'email.blank']),
            ],
            'visitDate' => [
                new Assert\NotBlank(['message' => 'date.unchosen']),
                new Assert\Regex([
                    'pattern' => '/^20[1-2][0-9]-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])' .
                                 ' ([0-1][0-9]|2[0-3]):[0-5][0-9]+/',
                    'message' => 'date.invalid',
                ]),
            ],
            'contractor' => [
                new Assert\NotBlank(['message' => 'provider.empty']),
            ]
        ]);

        $this->constraints = $validator->validate($input, $constraint);

        return $this->getErrorMessages();
    }

    /**
     * @return array
     */
    public function getErrorMessages(): array
    {
        $errors = array();
        foreach ($this->constraints as $constraint) {
            $errors[] = $constraint->getMessage();
        }
        return $errors;
    }
}

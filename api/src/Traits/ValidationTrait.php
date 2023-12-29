<?php

namespace App\Traits;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Constraints as Assert;

trait ValidationTrait
{
    /**
     * Serialize validation violations
     *
     * @param ConstraintViolationListInterface $violations
     * @return array
     */
    public function serializeViolations(ConstraintViolationListInterface $violations): array
    {
        $serializedViolations = [];

        foreach ($violations as $violation) {
            $serializedViolations[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $serializedViolations;
    }

    /**
     * Validate user data
     *
     * @param array $userData
     * @param array $photosData
     * @return array
     */
    public function validateUserData(array $userData, array $photosData): array
    {
        $errors = [];

        if (empty($userData['email']) || empty($userData['password']) || empty($userData['firstName']) || empty($userData['lastName'])) {
            $emptyFields = [];

            if (empty($userData['email'])) {
                $emptyFields[] = 'email';
            }

            if (empty($userData['password'])) {
                $emptyFields[] = 'password';
            }

            if (empty($userData['firstName'])) {
                $emptyFields[] = 'firstName';
            }

            if (empty($userData['lastName'])) {
                $emptyFields[] = 'lastName';
            }

            $errors['overall'] = 'Invalid Data: Empty field(s): ' . implode(', ', $emptyFields);
        }

        $firstNameViolations = $this->validator->validate($userData['firstName'], [
            new Assert\Length([
                'min' => 2,
                'max' => 25,
                'minMessage' => 'First name should be at least {{ limit }} characters long.',
                'maxMessage' => 'First name should be no longer than {{ limit }} characters.',
            ])
        ]);

        $lastNameViolations = $this->validator->validate($userData['lastName'], [
            new Assert\Length([
                'min' => 2,
                'max' => 25,
                'minMessage' => 'Last name should be at least {{ limit }} characters long.',
                'maxMessage' => 'Last name should be no longer than {{ limit }} characters.',
            ])
        ]);

        if (count($firstNameViolations) > 0) {
            $errors['First name'] = $this->serializeViolations($firstNameViolations);
        }

        if (count($lastNameViolations) > 0) {
            $errors['Last name'] = $this->serializeViolations($firstNameViolations);
        }

        $passwordViolations = $this->validator->validate($userData['password'], [
            new Assert\Length([
                'min' => 6,
                'max' => 50,
                'minMessage' => 'Your password should be at least {{ limit }} characters long.',
                'maxMessage' => 'Your password should be no longer than {{ limit }} characters.',
            ]),
            new Assert\Regex([
                'pattern' => '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{2,}$/',
                'message' => 'Password must contain at least one letter and one digit.',
            ]),
        ]);

        if (count($passwordViolations) > 0) {
            $errors['password'] = $this->serializeViolations($passwordViolations);
        }

        if (empty($photosData) || count($photosData) < 4) {
            $errors['photos'] = 'At least 4 photos should be uploaded';
        }

        return $errors;
    }

    /**
     * Validate authentication input data
     *
     * @param array $data
     * @return array
     */
    protected function validateAuthenticationInput(array $data): array
    {
        $errors = [];

        $constraints = new Assert\Collection([
            'email' => new Assert\NotBlank(['message' => 'Email cannot be blank.']),
            'password' => new Assert\NotBlank(['message' => 'Password cannot be blank.']),
        ]);

        $violations = $this->validator->validate($data, $constraints);

        if (count($violations) > 0) {
            $errors['errors'] = $this->serializeViolations($violations);
        }

        return $errors;
    }
}

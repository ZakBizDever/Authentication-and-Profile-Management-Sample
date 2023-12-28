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

        if (
            empty($userData['email'])
            || empty($userData['password'])
            || empty($userData['firstName'])
            || empty($userData['lastName'])
        ) {
            $errors['overall'] = 'Invalid Data';
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

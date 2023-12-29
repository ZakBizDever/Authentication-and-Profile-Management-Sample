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

        $this->validateRegistrationInput($userData, $errors);

        $this->validatePassword($userData['password'], $errors);

        $this->validatePhoto($photosData, $errors);

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

    /**
     * Registration form inputs validation
     *
     * @param $userData
     * @param $errors
     * @return void
     */
    private function validateRegistrationInput($userData, &$errors): void
    {
        $this->validateEmail($userData['email'], 'Email', $errors);

        $this->validateFieldLength($userData['firstName'], 'First name', 2, 25, $errors);

        $this->validateFieldLength($userData['lastName'], 'Last name', 2, 25, $errors);

        $emptyFields = array_filter(['email', 'password', 'firstName', 'lastName'], function ($field) use ($userData) {
            return empty($userData[$field]);
        });

        if (!empty($emptyFields)) {
            $errors['overall'] = 'Invalid Data: Empty field(s): ' . implode(', ', $emptyFields);
        }
    }

    /**
     *Validate provided field's Lenght against requirements
     *
     * @param $value
     * @param $fieldName
     * @param $min
     * @param $max
     * @param $errors
     * @return void
     */
    private function validateFieldLength($value, $fieldName, $min, $max, &$errors): void
    {
        $violations = $this->validator->validate($value, [
            new Assert\Length([
                'min' => $min,
                'max' => $max,
                'minMessage' => "$fieldName should be at least {{ limit }} characters long.",
                'maxMessage' => "$fieldName should be no longer than {{ limit }} characters.",
            ]),
        ]);

        if ($violations->count() > 0) {
            $errors[$fieldName] = $this->serializeViolations($violations);
        }
    }

    /**
     * Validate email structure
     *
     * @param $email
     * @param $fieldName
     * @param $errors
     * @return void
     */
    private function validateEmail($email, $fieldName, &$errors): void
    {
        $violations = $this->validator->validate($email, [
            new Assert\Email([
                'message' => "$fieldName is not a valid email address.",
            ]),
        ]);

        if ($violations->count() > 0) {
            $errors[$fieldName] = $this->serializeViolations($violations);
        }
    }

    /**
     * Validate password against requirements and complexity check
     *
     * @param $password
     * @param $errors
     * @return void
     */
    private function validatePassword($password, &$errors): void
    {
        $passwordViolations = $this->validator->validate($password, [
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
    }

    /**
     * Validate uploaded file type against image file possible extensions
     *
     * @param $photosData
     * @param $errors
     * @return void
     */
    private function validatePhoto($photosData, &$errors): void
    {
        foreach ($photosData as $index => $photo) {
            $photoViolations = $this->validator->validate($photo, [
                new Assert\File([
                    'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
                    'mimeTypesMessage' => 'Only JPEG, PNG, GIF, and WebP image types are allowed.',
                    'notFoundMessage' => 'File not found.',
                    'disallowEmptyMessage' => 'File should not be empty.',
                ]),
            ]);

            if (count($photoViolations) > 0) {
                $errors["photo{$index}"] = $this->serializeViolations($photoViolations);
            }
        }

        if (empty($photosData) || count($photosData) < 4) {
            $errors['photos'] = 'At least 4 photos should be uploaded';
        }
    }
}

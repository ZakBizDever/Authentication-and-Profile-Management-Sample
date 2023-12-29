<?php

namespace App\Service\User;

use App\Entity\User;
use App\Helper\FileUploaderHelper;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationService
{
    public function __construct(
        private EntityManagerInterface       $entityManager,
        private UserPasswordEncoderInterface $passwordEncoder,
        private ValidatorInterface           $validator,
        private FileUploaderHelper           $fileUploader
    )
    {
    }

    /**
     * Prepare and Insert User data (registration)
     *
     * @param array $userData
     * @param mixed $avatar
     * @param array $photosData
     * @return array|true
     */
    public function registerUser(array $userData, mixed $avatar, array $photosData): array|bool
    {
        try {
                $user = new User();

                $user->setFirstName($userData['firstName']);
                $user->setLastName($userData['lastName']);
                $user->setFullName($userData['firstName'] . ' ' . $userData['lastName']);
                $user->setEmail($userData['email']);
                $user->setRoles(['ROLE_USER']);

                $user->setPassword(
                    $this->passwordEncoder->encodePassword(
                        $user,
                        $userData['password']
                    )
                );

                $this->fileUploader->uploadUserPhotos($photosData, $user);

                $user->setAvatar($this->fileUploader->uploadAvatar($avatar));

                $this->entityManager->persist($user);
                $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            return ['error' => 'Account with this Email already registered!'];
        }

        return true;
    }
}

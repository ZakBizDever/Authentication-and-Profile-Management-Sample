<?php

namespace App\Service\User;

use App\Entity\User;
use App\Helper\FileUploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordEncoderInterface $passwordEncoder,
        private ValidatorInterface $validator,
        private FileUploaderHelper $fileUploader
    ) { }

    public function registerUser(array $userData, mixed $avatar, array $photosData): void
    {
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
    }
}
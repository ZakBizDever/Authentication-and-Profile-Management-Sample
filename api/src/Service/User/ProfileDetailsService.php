<?php

namespace App\Service\User;

use App\Entity\User;
use App\Repository\PhotoRepository;
use App\Repository\UserRepository;
use App\Helper\FileFetcherHelper;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUser;

class ProfileDetailsService
{
    public function __construct(
        private UserRepository    $userEntityRepository,
        private PhotoRepository   $photoEntityRepository,
        private FileFetcherHelper $fileFetcherHelper
    )
    {
    }

    /**
     * Extract and build user Data
     *
     * @param JWTUser $user
     * @return array
     * @throws NonUniqueResultException
     */
    public function getUserProfileData(JWTUser $user): array
    {
        $userData = $this->userEntityRepository->findOneItemBy(['email' => $user->getUsername()]);
        $userPhotos = $this->photoEntityRepository->findBy(['user' => $userData->getId()]);

        return [
            'fullName' => $userData->getFullName(),
            'email' => $userData->getEmail(),
            'avatar' => $this->fileFetcherHelper->loadUserPhotos(
                $userData->getAvatar(),
                $userData,
                reset($userPhotos)->getStorage()
            ),
            'photos' => $this->fileFetcherHelper->loadUserPhotos(
                $userPhotos,
                $userData,
                reset($userPhotos)->getStorage()
            ),
            'storage' => reset($userPhotos)->getStorage(),
            'active' => $userData->getActive(),
            'createdAt' => $userData->getCreatedAt(),
            'updatedAt' => $userData->getUpdatedAt(),
        ];
    }
}

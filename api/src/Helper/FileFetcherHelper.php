<?php

namespace App\Helper;

use App\Service\AWS\S3Uploader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FileFetcherHelper
{
    private $uploadStorage;

    public function __construct(
        private S3Uploader $s3Uploader,
        private ParameterBagInterface $parameterBag,
    )
    {
        $this->uploadStorage = $parameterBag->get('upload_storage');
    }

    /**
     * Get photo URL(s) from AWS S3.
     *
     * @param array|string $photoKey
     * @param $user
     * @return string|array|null
     */
    public function loadUserPhotos(array|string $photoKey, $user, $storage = 'local'): array|string|null
    {
        if (is_string($photoKey)) {
            if ($storage === 'aws') {
                return $this->s3Uploader->getPhotoUrl($photoKey);
            } elseif ($storage === 'local') {
                return $user->getAvatar();
            }
        } elseif (is_array($photoKey)) {
            $photoUrls = [];
            foreach ($photoKey as $singlePhotoKey) {
                if ($storage === 'aws') {
                    $photoUrls[] = $this->s3Uploader->getPhotoUrl($singlePhotoKey->getUrl());
                } elseif ($storage === 'local') {
                    $photoUrls[] = $singlePhotoKey->getUrl();
                }
            }

            return $photoUrls;
        } else {
            throw new \InvalidArgumentException('Invalid $photoKey. Expected string or array.');
        }

        return null;
    }
}

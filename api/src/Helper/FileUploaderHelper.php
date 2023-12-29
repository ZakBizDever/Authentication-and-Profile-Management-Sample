<?php

namespace App\Helper;

use App\Entity\Photo;
use App\Service\AWS\S3Uploader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploaderHelper
{
    private $uploadStorage;

    public function __construct(
        private S3Uploader            $s3Uploader,
        private ParameterBagInterface $parameterBag,
    )
    {
        $this->uploadStorage = $parameterBag->get('upload_storage');
    }

    /**
     * Upload user's avatar to specified storage
     *
     * @param UploadedFile|null $filePath
     * @return string
     */
    public function uploadAvatar(?UploadedFile &$filePath): string
    {
        if (!$filePath) return $filePath = $this->parameterBag->get('avatars_directory') . '/' . 'default_avatar.webp';

        if (!file_exists($filePath) || !is_readable($filePath)) {
            return 'Invalid file path.';
        }

        if ($this->uploadStorage === 'aws') {
            return $this->s3Uploader->uploadPhoto($filePath, 'avatars');
        } elseif ($this->uploadStorage === 'local') {
            $avatarName = uniqid() .
                '-' .
                pathinfo($filePath, PATHINFO_FILENAME) .
                '.' .
                $filePath->guessExtension();

            // Open the file only when needed, for Lazy-Loading purposes
            $file = fopen($filePath, 'r');

            $targetPath = $this->parameterBag->get('avatars_directory') . '/' . $avatarName;
            $targetFile = fopen($targetPath, 'w');

            while (!feof($file)) {
                fwrite($targetFile, fread($file, 8192)); // Read and write in chunks of (8 KB)
            }

            fclose($file);
            fclose($targetFile);

            return $this->extractImagePath($targetPath);
        }

        return 'Error in storage config.';
    }

    /**
     * Upload user's photos to specified storage
     *
     * @param array $photos
     * @param $user
     * @return array
     */
    public function uploadUserPhotos(array $photos, $user): array
    {
        $photoKeys = [];

        foreach ($photos as $photoData) {
            $photoUrl = $photoFilename = '';
            $uploadStorage = $this->uploadStorage;

            if ($uploadStorage === 'aws') {
                $photoUrl = $photoFilename = $this->s3Uploader->uploadPhoto($photoData, 'photos');
                $photoKeys[] = $photoUrl;
            } elseif ($uploadStorage === 'local') {
                if (is_resource($photoData) && get_resource_type($photoData) === 'stream') {
                    $photoFilename =
                        md5(uniqid()) .
                        '-' .
                        pathinfo($photoData, PATHINFO_FILENAME) .
                        '.' .
                        pathinfo($photoData, PATHINFO_EXTENSION);
                    $photoUrl = $this->parameterBag->get('photos_directory') . '/' . $photoFilename;

                    // For lazy loading purpose
                    $file = fopen($photoData, 'r');

                    $targetPath = $this->parameterBag->get('photos_directory') . '/' . $photoFilename;
                    $targetFile = fopen($targetPath, 'w');

                    while (!feof($file)) {
                        fwrite($targetFile, fread($file, 8192)); // Read and write in chunks (8 KB)
                    }

                    fclose($file);
                    fclose($targetFile);

                    $photoKeys[] = $this->extractImagePath($targetPath);
                } elseif ($photoData instanceof UploadedFile) {
                    $photoFilename = md5(uniqid()) .
                        '-' .
                        $photoData->getBasename() .
                        '.' . $photoData->guessExtension();
                    $photoUrl = $this->parameterBag->get('photos_directory') . '/' . $photoFilename;
                    $photoKeys[] = $photoData->move($this->parameterBag->get('photos_directory'), $photoFilename);
                }
            }

            $photo = new Photo();
            $photo->setName($photoFilename);
            $photo->setUrl($this->extractImagePath($photoUrl));
            $photo->setStorage($uploadStorage);

            $user->addPhoto($photo);
        }

        return $photoKeys;
    }

    /**
     * Trim and prepare upload path for client use
     *
     * @param $inputString
     * @return mixed|string
     */
    private function extractImagePath($inputString): mixed
    {
        $position = strpos($inputString, '/uploads/');

        if ($position !== false) {
            $extractedPath = substr($inputString, $position);
            return $extractedPath;
        }

        return $inputString;
    }
}

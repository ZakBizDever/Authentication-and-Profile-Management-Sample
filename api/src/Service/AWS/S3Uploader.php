<?php

namespace App\Service\AWS;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class S3Uploader
{
    private $s3Client;
    private $bucketName;

    public function __construct(
        string                  $awsAccessKeyId,
        string                  $awsSecretAccessKey,
        string                  $awsRegion,
        string                  $bucketName)
    {
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region' => $awsRegion,
            'credentials' => [
                'key' => $awsAccessKeyId,
                'secret' => $awsSecretAccessKey,
            ],
        ]);

        $this->bucketName = $bucketName;
    }

    /**
     * Upload photo to S3 Bucket
     *
     * @param UploadedFile $photo
     * @param string $folder
     * @return string
     */
    public function uploadPhoto(UploadedFile $photo, string $folder): string
    {
        $photoKey = $folder . '/' . uniqid() . '.' . $photo->getClientOriginalExtension();

        $this->s3Client->putObject([
            'Bucket' => $this->bucketName,
            'Key' => $photoKey,
            'Body' => fopen($photo->getPathname(), 'rb'),
            'ACL' => 'public-read',
        ]);

        return $photoKey;
    }

    /**
     * Get photo URL in S3 Bucket
     *
     * @param string $photoKey
     * @return string|null
     */
    public function getPhotoUrl(string $photoKey): ?string
    {
        try {
            $command = $this->s3Client->getCommand('GetObject', [
                'Bucket' => $this->bucketName,
                'Key' => $photoKey,
            ]);

            $request = $this->s3Client->createPresignedRequest($command, '+20 minutes');

            return (string)$request->getUri();
        } catch (AwsException $e) {
            return null;
        }
    }
}

<?php

namespace App\Services\Aws;

use Aws\S3\S3Client;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class S3
{
    private const SIGNED_URL_DURATION = '20 minutes';

    public function __construct(
        private S3Client $client,
        private string $bucket = '',
    ) {
        $this->bucket = config('aws.s3.bucket');
    }

    public function put(string $filename, string $path): string
    {
        $response = $this->client->putObject([
            'Bucket' => $this->bucket,
            'Key' => $filename,
            'SourceFile' => $path,
        ]);

        return $response->get('ObjectURL');
    }

    public function deleteBulk(array $filenames): bool
    {
        $keys = array_map(function (string $filename) {
            return ['Key' => $filename];
        }, $filenames);

        $response = $this->client->deleteObjects([
            'Bucket' => $this->bucket,
            'Delete' => [
                'Objects' => $keys,
            ],
        ]);

        return $response->get('@metadata')['statusCode'] === ResponseAlias::HTTP_OK;
    }

    public function url(string $filename): string
    {
        return $this->client->getObjectUrl(
            bucket: $this->bucket,
            key: $filename
        );
    }

    public function signedUrl(string $filename): string
    {
        $command = $this->client->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key' => $filename,
        ]);

        $signedRequest = $this->client->createPresignedRequest($command, self::SIGNED_URL_DURATION);

        $url = $signedRequest->getUri();

        return sprintf('%s://%s%s?%s', $url->getScheme(), $url->getHost(), $url->getPath(), $url->getQuery());
    }
}

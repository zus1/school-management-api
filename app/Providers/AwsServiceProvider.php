<?php

namespace App\Providers;

use Aws\Credentials\CredentialProvider;
use Aws\Credentials\Credentials;
use Aws\S3\S3Client;
use Aws\Sns\SnsClient;
use Illuminate\Support\ServiceProvider;

class AwsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(SnsClient::class, function () {
            return new SnsClient([
                'version' => config('aws.sns.version'),
                'region' => config('aws.region'),
                'credentials' => $this->getCredentials(),
            ]);
        });

        $this->app->bind(S3Client::class, function () {
            return new S3Client([
                'version' => config('aws.s3.version'),
                'region' => config('aws.region'),
                'credentials' =>$this->getCredentials(),
            ]);
        });
    }

    private function getCredentials(): callable
    {
        return CredentialProvider::fromCredentials(new Credentials(
            key: config('aws.key'),
            secret: config('aws.secret')
        ));
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

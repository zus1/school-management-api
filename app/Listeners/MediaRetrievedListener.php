<?php

namespace App\Listeners;

use App\Events\MediaRetrieved;
use App\Services\Aws\S3;

class MediaRetrievedListener
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private S3 $s3,
    ){
    }

    /**
     * Handle the event.
     */
    public function handle(MediaRetrieved $event): void
    {
        $media = $event->getMedia();

        if($media->media === null) {
            return;
        }

        $media->media = $this->s3->signedUrl($media->media);
    }
}

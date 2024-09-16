<?php

namespace App\Models;

use App\Events\MediaRetrieved;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property string $media
 * @property string $type
 */
#[Attributes([
    ['id', 'media:upload'],
    ['media',
        'media:nestedStudentRetrieve', 'media:nestedTeacherRetrieve', 'media:nestedGuardianRetrieve', 'media:upload',
        'media:nestedQuestionRetrieve'
    ],
    ['type', 'media:upload', 'media:nestedQuestionRetrieve'],
    ['mediaOwner', 'mediaUpload']
])]
class Media extends Model
{
    use HasFactory;

    public function mediaOwner(): MorphTo
    {
        return $this->morphTo();
    }

    public $dispatchesEvents = [
        'retrieved' => MediaRetrieved::class,
        'created' => MediaRetrieved::class,
    ];
}

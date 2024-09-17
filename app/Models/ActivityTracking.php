<?php

namespace App\Models;

use App\Trait\Mutator\CreatedAtMutator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property string $created_at
 * @property string $route
 * @property int $student_id
 */
#[Attributes([
    ['created_at', 'activityTracking:collection'],
    ['route', 'activityTracking:collection'],
    ['student', 'activityTracking:collection'],
])]
class ActivityTracking extends Model
{
    use HasFactory, CreatedAtMutator;

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}

<?php

namespace App\Models;

use App\Trait\Mutator\CreatedAtMutator;
use Iksaku\Laravel\MassUpdate\MassUpdatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property string $last_paid
 * @property string $due_at
 * @property string $last_reminder_sent_at
 * @property int $payment_id
 * @property int $guardian_id
 * @property int $student_id
 * @property string $created_at
 * @property string $status
 * @property Student $student
 * @property Guardian $guardian
 * @method static massUpdate(array $objects)
 */
#[Attributes([
    ['created_at', 'tuition:nestedStudentRetrieve'],
    ['last_paid', 'tuition:nestedStudentRetrieve'],
    ['due_at', 'tuition:nestedStudentRetrieve'],
    ['status', 'tuition:nestedStudentRetrieve'],
])]
class Tuition extends Model
{
    use HasFactory, CreatedAtMutator, MassUpdatable;

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

    public function guardian(): BelongsTo
    {
        return $this->belongsTo(Guardian::class, 'guardian_id', 'id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}

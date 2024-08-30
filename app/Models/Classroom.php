<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $max_capacity
 * @property int $floor
 * @property string $number
 * @property string $size
 * @property int $number_of_seats
 * @property string $purpose
 */
#[Attributes([
    ['id',
        'classroom:nestedSubjectEventCreate', 'classroom:nestedSubjectEventUpdate', 'classroom:nestedSubjectEventRetrieve',
        'classroom:create', 'classroom:retrieve', 'classroom:collection', 'classroom:updateEquipmentQuantity'
    ],
    ['name',
        'classroom:nestedSubjectEventCreate', 'classroom:nestedSubjectEventUpdate', 'classroom:nestedSubjectEventRetrieve',
        'classroom:create', 'classroom:update', 'classroom:retrieve', 'classroom:collection', 'classroom:updateEquipmentQuantity'
    ],
    ['floor',
        'classroom:nestedSubjectEventCreate', 'classroom:nestedSubjectEventUpdate', 'classroom:nestedSubjectEventRetrieve',
        'classroom:create', 'classroom:update', 'classroom:retrieve', 'classroom:collection'
    ],
    ['number',
        'classroom:nestedSubjectEventCreate', 'classroom:nestedSubjectEventUpdate','classroom:nestedSubjectEventRetrieve',
        'classroom:create', 'classroom:update', 'classroom:retrieve', 'classroom:collection'
    ],
    ['description', 'classroom:create', 'classroom:update', 'classroom:retrieve'],
    ['max_capacity', 'classroom:create', 'classroom:update', 'classroom:retrieve'],
    ['size', 'classroom:create', 'classroom:update', 'classroom:retrieve'],
    ['number_of_seats', 'classroom:create', 'classroom:update', 'classroom:retrieve'],
    ['purpose', 'classroom:create', 'classroom:update', 'classroom:retrieve'],
    ['equipments', 'classroom:toggleEquipment', 'classroom:retrieve', 'classroom:updateEquipmentQuantity']
])]
class Classroom extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function equipments(): BelongsToMany
    {
        return $this->belongsToMany(
            Equipment::class,
            'classrooms_equipments',
            'classroom_id',
            'equipment_id'
        );
    }
}

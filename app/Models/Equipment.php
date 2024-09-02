<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $type
 * @property int $total_quantity
 * @property int $available_quantity
 * @property int $weight
 * @property int $length
 * @property int $height
 * @property int $width
 * @property float $cost
 * @property float $cost_per_unit
 */
#[Attributes([
    ['id',
        'equipment:create', 'equipment:collection', 'equipment:retrieve', 'equipment:nestedClassroomToggleEquipment',
        'equipment:nestedClassroomRetrieve', 'equipment:nestedClassroomUpdateEquipmentQuantity'
    ],
    ['name',
        'equipment:create', 'equipment:update', 'equipment:collection',
        'equipment:retrieve', 'equipment:nestedClassroomToggleEquipment', 'equipment:nestedClassroomRetrieve',
        'equipment:nestedClassroomUpdateEquipmentQuantity'
    ],
    ['description',
        'equipment:create', 'equipment:update', 'equipment:retrieve', 'equipment:nestedClassroomToggleEquipment',
        'equipment:nestedClassroomRetrieve', 'equipment:nestedClassroomUpdateEquipmentQuantity'
    ],
    ['type',
        'equipment:create', 'equipment:update', 'equipment:retrieve', 'equipment:nestedClassroomToggleEquipment',
        'equipment:nestedClassroomRetrieve', 'equipment:nestedClassroomUpdateEquipmentQuantity'
    ],
    ['total_quantity', 'equipment:create', 'equipment:update', 'equipment:collection', 'equipment:retrieve'],
    ['available_quantity', 'equipment:create', 'equipment:update', 'equipment:collection', 'equipment:retrieve'],
    ['weight', 'equipment:create', 'equipment:update', 'equipment:retrieve'],
    ['length', 'equipment:create', 'equipment:update', 'equipment:retrieve'],
    ['height', 'equipment:create', 'equipment:update', 'equipment:retrieve'],
    ['width', 'equipment:create', 'equipment:update', 'equipment:retrieve'],
    ['cost', 'equipment:create', 'equipment:update', 'equipment:collection', 'equipment:retrieve'],
    ['cost_per_unit', 'equipment:create', 'equipment:update', 'equipment:retrieve'],
])]
class Equipment extends Model
{
    use HasFactory;

    public $table = 'equipments';

    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(
            Classroom::class,
            'classrooms_equipments',
            'equipment_id',
            'classroom_id'
        );
    }
}

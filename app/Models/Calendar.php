<?php

namespace App\Models;

use App\Interface\CanBeActiveInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property bool $active
 */
#[Attributes([
    ['id', 'calendar:create', 'calendar:collection', 'calendar:nestedEventCreate', 'calendar:nestedEventCollection'],
    ['name',
        'calendar:create', 'calendar:update', 'calendar:collection', 'calendar:nestedEventCreate',
        'calendar:nestedEventCollection'
    ],
    ['description', 'calendar:create', 'calendar:update', 'calendar:collection', 'calendar:nestedEventCollection'],
    ['active', 'calendar:create', 'calendar:toggleActive']
])]
class Calendar extends Model implements CanBeActiveInterface
{
    use HasFactory;

    public function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'calendar_id', 'id');
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Zus1\Discriminator\Observers\DiscriminatorObserver;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property string $street
 * @property string $occupation
 * @property string $city
 */
#[Attributes([
    ['id', 'user:register', 'user:me', 'guardian:retrieve', 'guardian:collection', 'mediaOwner:nestedMediaUpload'],
    ['street', 'user:register', 'user:me', 'user:meUpdate', 'guardian:update', 'guardian:retrieve', 'guardian:collection'],
    ['occupation', 'user:register', 'user:me', 'user:meUpdate', 'guardian:update', 'guardian:retrieve'],
    ['city', 'guardian:update', 'user:meUpdate', 'guardian:retrieve', 'guardian:collection', 'user:register'],
    ['parent',
        'user:me', 'user:meUpdate', 'guardian:update', 'guardian:retrieve', 'user:register',
        'mediaOwner:nestedMediaUpload'
    ],
])]
#[ObservedBy(DiscriminatorObserver::class)]
class Guardian extends User
{
    use HasFactory;

    public $timestamps = false;

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'guardian_id', 'id');
    }
}

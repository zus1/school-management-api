<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property int $year
 */
#[Attributes([
    ['id', 'schoolYear:nestedSchoolClassCreate', 'schoolYear:nestedSchoolClassUpdate'],
    ['year', 'schoolYear:nestedSchoolClassCreate', 'schoolYear:nestedSchoolClassUpdate'],
])]
class SchoolYear extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    public function schoolClasses(): HasMany
    {
        return $this->hasMany(SchoolClass::class, 'school_year_id', 'id');
    }

    public function subjects(): HasMAny
    {
        return $this->hasMany(Subject::class, 'school_year_id', 'id');
    }
}

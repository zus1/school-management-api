<?php

namespace App\Models;

use App\Interface\CanBeActiveInterface;
use App\Interface\MediasOwnerInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Zus1\Discriminator\Trait\Discriminator;
use Zus1\LaravelAuth\Trait\Token;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property string $email
 * @property string $password
 * @property array $roles
 * @property string $first_name
 * @property string $last_name
 * @property string $gender
 * @property string $dob
 * @property string $phone
 * @property int $active
 * @property int $phone_verified
 * @property int $child_id
 */
#[Attributes([
    ['id', 'user:register', 'user:me'],
    ['email',
        'user:register', 'student:create', 'user:me', 'student:retrieve', 'student:collection',
        'teacher:retrieve', 'teacher:collection', 'guardian:retrieve', 'guardian:collection',
        'user:toggleActive', 'user:nestedEventCreate', 'user:nestedEventRetrieve'
    ],
    ['roles', 'user:register', 'student:create', 'user:me', 'teacher:retrieve'],
    ['first_name',
        'user:register', 'student:onboard', 'user:me', 'user:meUpdate',
        'teacher:nestedSchoolClassCreate', 'teacher:nestedSchoolClassUpdate',
        'student:update', 'student:retrieve', 'student:collection', 'teacher:update', 'teacher:retrieve',
        'teacher:collection', 'guardian:update', 'guardian:retrieve',
        'guardian:collection', 'user:nestedEventCreate', 'user:nestedEventRetrieve',
        'teacher:nestedSubjectEventCreate', 'teacher:nestedSubjectEventUpdate',
        'teacher:nestedSubjectEventRetrieve', 'user:nestedEventToggleNotify',
        'teacher:nestedExamEventCreate', 'teacher:nestedExamEventUpdate', 'teacher:nestedExamEventRetrieve',
        'teacher:nestedSubjectRetrieve', 'teacher:nestedSubjectToggleLecturer',
        'teacher:nestedTeacherSubjectRetrieve', 'teacher:nestedTeacherSubjectCollection', 'teacher:nestedToggleLecturerClasses',
        'teacher:nestedSubject', 'user:nestedMessageCreate', 'user:nestedMessageRetrieve', 'user:nestedMessageCollection',
        'teacher:nestedGradeCreate', 'student:nestedGradeCreate', 'teacher:nestedGradeCollection',
        'student:nestedGradeCollection', 'teacher:nestedAttendanceCreate', 'student:nestedAttendanceCreate',
        'teacher:nestedAttendanceCollection', 'student:nestedAttendanceCollection', 'student:nestedAttendanceAggregate',
        'teacher:nestedAttendanceAggregate', 'teacher:nestedExamRetrieve', 'mediaOwner:nestedMediaUpload',
        'student:nestedExamCollection', 'student:nestedExamSessionRetrieve'
    ],
    ['last_name',
        'user:register', 'student:onboard', 'user:me', 'user:meUpdate',
        'teacher:nestedSchoolClassCreate', 'teacher:nestedSchoolClassUpdate',
        'student:update', 'student:retrieve', 'student:collection', 'teacher:update', 'teacher:retrieve',
        'teacher:collection', 'guardian:update', 'guardian:retrieve',
        'guardian:collection', 'user:nestedEventCreate', 'user:nestedEventRetrieve',
        'teacher:nestedSubjectEventCreate', 'teacher:nestedSubjectEventUpdate',
        'teacher:nestedSubjectEventRetrieve', 'user:nestedEventToggleNotify',
        'teacher:nestedExamEventCreate', 'teacher:nestedExamEventUpdate', 'teacher:nestedExamEventRetrieve',
        'teacher:nestedSubjectRetrieve', 'teacher:nestedSubjectToggleLecturer',
        'teacher:nestedTeacherSubjectRetrieve', 'teacher:nestedTeacherSubjectCollection', 'teacher:nestedToggleLecturerClasses',
        'teacher:nestedSubject', 'user:nestedMessageCreate', 'user:nestedMessageRetrieve', 'user:nestedMessageCollection',
        'teacher:nestedGradeCreate', 'student:nestedGradeCreate', 'teacher:nestedGradeCollection',
        'student:nestedGradeCollection', 'teacher:nestedAttendanceCreate', 'student:nestedAttendanceCreate',
        'teacher:nestedAttendanceCollection', 'student:nestedAttendanceCollection', 'student:nestedAttendanceAggregate',
        'teacher:nestedAttendanceAggregate', 'teacher:nestedExamRetrieve', 'mediaOwner:nestedMediaUpload',
        'student:nestedExamCollection', 'student:nestedExamSessionRetrieve'
    ],
    ['gender',
        'user:register', 'student:onboard', 'user:me', 'user:meUpdate', 'student:update',
        'student:retrieve', 'student:collection', 'teacher:update', 'teacher:retrieve','guardian:update', 'guardian:retrieve'
    ],
    ['dob',
        'user:register', 'student:onboard', 'user:me', 'user:meUpdate', 'student:update',
        'student:retrieve', 'teacher:update', 'teacher:retrieve', 'guardian:update', 'guardian:retrieve'
    ],
    ['phone',
        'user:register', 'user:verifyPhone', 'student:create', 'user:me', 'student:retrieve',
        'teacher:retrieve', 'guardian:retrieve'
    ],
    ['phone_verified',
        'user:verifyPhone', 'student:create', 'user:me', 'student:retrieve',
        'teacher:retrieve', 'guardian:retrieve'
    ],
    ['active',
        'user:register', 'student:create', 'student:onboard', 'user:me', 'guardian:retrieve',
        'student:retrieve', 'student:collection', 'teacher:retrieve', 'teacher:collection',
        'guardian:collection', 'user:toggleActive'
    ],
    ['medias', 'user:me', 'student:retrieve', 'teacher:retrieve', 'guardian:retrieve']
])]
class User extends Authenticatable implements CanBeActiveInterface, MediasOwnerInterface
{
    use HasFactory, Notifiable, Discriminator, Token;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    public function medias(): MorphMany
    {
        return $this->morphMany(Media::class, 'media_owner');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'active' => 'boolean',
            'phone_verified' => 'boolean',
            'roles' => 'array',
        ];
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles);
    }

    public function hasOneOfRoles(array $roles): bool
    {
        return array_intersect($roles, $this->roles) !== [];
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'creator_id', 'id');
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id', 'id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'recipient_id', 'id');
    }

    public function mediaOwnerId(): int
    {
        /** @var self $child */
        $child = $this->child()->first();

        return $child->id;
    }
}

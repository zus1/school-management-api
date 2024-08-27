<?php

namespace App\Models;

use App\Constant\Calendar\CalendarEventStatus;
use App\Interface\CloneableInterface;
use Carbon\Carbon;
use Iksaku\Laravel\MassUpdate\MassUpdatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\QueryException;
use Zus1\Discriminator\Trait\Discriminator;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $starts_at
 * @property string $ends_at
 * @property int $duration
 * @property string $title
 * @property string $content
 * @property string $status
 * @property string $repeatable_status
 * @property int $creator_id
 * @property int $notifications_sent
 * @property $notifyUsers
 * @property $child
 * @property $child_id
 * @method static massUpdate(array $objects)
 */
#[Attributes([
    ['id', 'event:create', 'event:retrieve', 'event:collection'],
    ['starts_at', 'event:create', 'event:update', 'event:collection', 'event:retrieve'],
    ['ends_at', 'event:create', 'event:update', 'event:collection', 'event:retrieve'],
    ['duration', 'event:create', 'event:update', 'event:retrieve'],
    ['title', 'event:create', 'event:update', 'event:collection', 'event:retrieve', 'event:toggleNotify'],
    ['content', 'event:create', 'event:update', 'event:retrieve'],
    ['status', 'event:create', 'event:update', 'event:collection', 'event:retrieve', 'event:updateStatus'],
    ['repeatable_status', 'event:create', 'event:update', 'event:retrieve', 'event:updateRepeatableStatus'],
    ['calendar', 'event:create'],
    ['notifyUsers', 'event:create', 'event:retrieve', 'event:toggleNotify'],
])]
class Event extends Model implements CloneableInterface
{
    use HasFactory, Discriminator, MassUpdatable;

    public const CREATED_AT = 'Y-m-d H:i:s';
    protected $dateFormat = 'Y-m-d H:i:s';

    private string $discriminatorParent = Event::class;

    private int $currentChildId;

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function notifyUsers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'event_user_notification',
            'event_id',
            'user_id'
        );
    }

    public function calendar(): BelongsTo
    {
        return $this->belongsTo(Calendar::class, 'calendar_id', 'id');
    }

    public function clone(): self
    {
        $clone = new self();
        $this->cloneBaseAttributes($clone);

        try {
            /** @var Event $child */
            $child = $this->child()->first();
        } catch (QueryException) {
            return $clone;
        }

        $clone->child = $child->clone();

        return $clone;
    }

    protected function cloneBaseAttributes(Event $clone): void
    {
        $attributes = $this->getAttributes();
        unset($attributes['id']);
        unset($attributes['updated_at']);
        $clone->setRawAttributes($attributes);

        $clone->status = CalendarEventStatus::SCHEDULED;
        $clone->created_at = Carbon::now()->format('Y-m-d H:i:s');

        $clone->setPreservedIdentifier($this->child_id);
        $clone->child_id = null;
    }

    public function setPreservedIdentifier(int $currentChildId): void
    {
        $this->currentChildId = $currentChildId;
    }

    public function getPreservedIdentifier(): int
    {
        return $this->currentChildId;
    }
}

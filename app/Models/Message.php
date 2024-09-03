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
 * @property int sender_id
 * @property int $recipient_id
 * @property string $created_at
 * @property string $title
 * @property string $content
 * @property bool $is_read
 * @property string $read_at
 * @method static massUpdate(array $objects)
 */
#[Attributes([
    ['id', 'message:create', 'message:retrieve', 'message:collection', 'message:markAsRead'],
    ['title', 'message:create', 'message:update', 'message:retrieve', 'message:collection', 'message:markAsRead'],
    ['content', 'message:create', 'message:update', 'message:retrieve'],
    ['created_at', 'message:create', 'message;retrieve', 'message:collection'],
    ['is_read', 'message:create', 'message:retrieve', 'message:collection', 'message:markAsRead'],
    ['read_at', 'message:markAsRead'],
    ['sender', 'message:create', 'message:retrieve', 'message:collection'],
    ['recipient', 'message:create', 'message:retrieve', 'message:collection'],
])]
class Message extends Model
{
    use HasFactory, MassUpdatable, CreatedAtMutator;

    public $dateFormat = 'Y-m-d H:i:s';

    public function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Notification extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id', 'user_id', 'type', 'data', 'read_at',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'read_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($notification) {
            if (!$notification->id) {
                $notification->id = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function send(int $userId, string $type, array $data): self
    {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'data' => $data,
        ]);
    }
}

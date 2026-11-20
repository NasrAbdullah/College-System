<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'body',
        'created_by',
        'published_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'announcement_roles')
            ->using(AnnouncementRole::class);
    }

    public function isPublished(): bool
    {
        return $this->published_at !== null
            && $this->published_at->lessThanOrEqualTo(now())
            && ($this->expires_at === null || $this->expires_at->isFuture());
    }

    public function isVisibleTo(User $user): bool
    {
        if (! $user->is_active || ! $this->isPublished()) {
            return false;
        }

        return $this->roles()->whereHas(
            'users',
            fn (Builder $query) => $query->whereKey($user->getKey())
        )->exists();
    }

    #[Scope]
    protected function published(Builder $query): void
    {
        $now = now();

        $query->whereNotNull('published_at')
            ->where('published_at', '<=', $now)
            ->where(function (Builder $query) use ($now): void {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', $now);
            });
    }
}

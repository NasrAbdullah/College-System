<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    
    // Deliberately not using Laravel's Notifiable trait:
    // our notifications table has a custom schema, not the framework's polymorphic schema.

    protected $fillable = [
        'name',
        'email',
        'login_name',
        'password',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
            'email_verified_at' => 'datetime',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->using(UserRole::class)
            ->withPivot(['assigned_at', 'assigned_by']);
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    public function assignedRoles(): HasMany
    {
        return $this->hasMany(UserRole::class, 'assigned_by');
    }

    public function issuedActivations(): HasMany
    {
        return $this->hasMany(StudentActivation::class, 'issued_by');
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }

    public function portalNotifications(): HasMany
    {
        return $this->hasMany(PortalNotification::class, 'user_id');
    }

    public function uploadedAttachments(): HasMany
    {
        return $this->hasMany(RequestAttachment::class, 'uploaded_by');
    }

    public function requestMessages(): HasMany
    {
        return $this->hasMany(RequestMessage::class, 'author_user_id');
    }

    public function requestStatusChanges(): HasMany
    {
        return $this->hasMany(RequestStatusHistory::class, 'changed_by');
    }

    public function hasRole(string $roleCode): bool
    {
        if (! $this->is_active) {
            return false;
        }

        return $this->roles()->where('roles.code', $roleCode)->exists();
    }

    public function hasPermission(string $permissionCode): bool
    {
        if (! $this->is_active) {
            return false;
        }

        return $this->roles()->whereHas(
            'permissions',
            fn (Builder $query) => $query->where('permissions.code', $permissionCode)
        )->exists();
    }

    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true);
    }
}

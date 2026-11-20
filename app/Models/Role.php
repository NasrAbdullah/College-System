<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = ['code', 'name'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles')
            ->using(UserRole::class)
            ->withPivot(['assigned_at', 'assigned_by']);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
            ->using(RolePermission::class);
    }

    public function announcements(): BelongsToMany
    {
        return $this->belongsToMany(Announcement::class, 'announcement_roles')
            ->using(AnnouncementRole::class);
    }

    public function grants(string $permissionCode): bool
    {
        return $this->permissions()->where('permissions.code', $permissionCode)->exists();
    }

    #[Scope]
    protected function withCode(Builder $query, string $code): void
    {
        $query->where('code', $code);
    }
}

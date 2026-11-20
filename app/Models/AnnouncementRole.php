<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AnnouncementRole extends Pivot
{
    protected $table = 'announcement_roles';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = ['announcement_id', 'role_id'];

    public function announcement(): BelongsTo
    {
        return $this->belongsTo(Announcement::class, 'announcement_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}

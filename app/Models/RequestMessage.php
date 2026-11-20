<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestMessage extends Model
{
    public const CREATED_AT = 'created_at';

    public const UPDATED_AT = null;

    public const STUDENT_VISIBLE = 'student_visible';

    public const STAFF_ONLY = 'staff_only';

    protected $fillable = [
        'service_request_id',
        'author_user_id',
        'body',
        'visibility',
    ];

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_user_id');
    }

    public function isVisibleToStudent(): bool
    {
        return $this->visibility === self::STUDENT_VISIBLE;
    }

    #[Scope]
    protected function studentVisible(Builder $query): void
    {
        $query->where('visibility', self::STUDENT_VISIBLE);
    }

    #[Scope]
    protected function forRequest(Builder $query, ServiceRequest $request): void
    {
        $query->where('service_request_id', $request->getKey());
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ServiceRequest extends Model
{
    public const STATUS_SUBMITTED = 'submitted';

    public const STATUS_UNDER_REVIEW = 'under_review';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'student_id',
        'service_type_id',
        'subject',
        'details',
        'status',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return ['submitted_at' => 'datetime'];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }

    public function absenceDetail(): HasOne
    {
        return $this->hasOne(AbsenceRequestDetail::class, 'service_request_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(RequestAttachment::class, 'service_request_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(RequestMessage::class, 'service_request_id');
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(RequestStatusHistory::class, 'service_request_id');
    }

    public function isAbsenceRequest(): bool
    {
        return $this->serviceType?->code === ServiceType::ABSENCE;
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->student()->where('user_id', $user->getKey())->exists();
    }

    #[Scope]
    protected function forStudent(Builder $query, Student $student): void
    {
        $query->where('student_id', $student->getKey());
    }

    #[Scope]
    protected function withStatus(Builder $query, string $status): void
    {
        $query->where('status', $status);
    }
}

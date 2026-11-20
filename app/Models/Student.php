<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'emtiaz_student_id',
        'academic_number',
        'user_id',
        'last_verified_at',
    ];

    protected function casts(): array
    {
        return ['last_verified_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function activations(): HasMany
    {
        return $this->hasMany(StudentActivation::class, 'student_id');
    }

    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'student_id');
    }

    public function hasAccount(): bool
    {
        return $this->user_id !== null;
    }

    public function hasVerifiedExternalLink(): bool
    {
        return $this->emtiaz_student_id !== null && $this->last_verified_at !== null;
    }

    #[Scope]
    protected function withAcademicNumber(Builder $query, string $academicNumber): void
    {
        $query->where('academic_number', $academicNumber);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;

class StudentActivation extends Model
{
    public const CREATED_AT = 'created_at';

    public const UPDATED_AT = null;

    public const PURPOSE_ACTIVATE = 'activate_account';

    public const PURPOSE_RESET_PASSWORD = 'reset_password';

    protected $fillable = [
        'student_id',
        'purpose',
        'token_hash',
        'issued_by',
        'expires_at',
        'used_at',
        'revoked_at',
        'failed_attempts',
    ];

    protected $hidden = ['token_hash'];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
            'revoked_at' => 'datetime',
            'failed_attempts' => 'integer',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function isUsable(int $maxFailedAttempts): bool
    {
        return $this->used_at === null
            && $this->revoked_at === null
            && $this->expires_at !== null
            && $this->expires_at->isFuture()
            && $this->failed_attempts < $maxFailedAttempts;
    }

    public function matchesToken(string $plainToken): bool
    {
        return Hash::check($plainToken, $this->token_hash);
    }

    #[Scope]
    protected function forPurpose(Builder $query, string $purpose): void
    {
        $query->where('purpose', $purpose);
    }

    #[Scope]
    protected function unconsumed(Builder $query): void
    {
        $query->whereNull('used_at')
            ->whereNull('revoked_at')
            ->where('expires_at', '>', now());
    }
}

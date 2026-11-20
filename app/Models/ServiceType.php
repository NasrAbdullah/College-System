<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceType extends Model
{
    public const ABSENCE = 'absence';

    public const CERTIFICATE = 'certificate';

    public const DATA_CORRECTION = 'data_correction';

    public const COMPLAINT = 'complaint';

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_enabled',
    ];

    protected function casts(): array
    {
        return ['is_enabled' => 'boolean'];
    }

    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'service_type_id');
    }

    public function isEnabled(): bool
    {
        return $this->is_enabled;
    }

    #[Scope]
    protected function enabled(Builder $query): void
    {
        $query->where('is_enabled', true);
    }

    #[Scope]
    protected function withCode(Builder $query, string $code): void
    {
        $query->where('code', $code);
    }
}

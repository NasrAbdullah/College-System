<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class IntegrationLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'system_code',
        'operation',
        'correlation_id',
        'outcome',
        'http_status',
        'error_code',
        'occurred_at',
    ];

    protected function casts(): array
    {
        return [
            'http_status' => 'integer',
            'occurred_at' => 'datetime',
        ];
    }

    #[Scope]
    protected function forSystem(Builder $query, string $code): void
    {
        $query->where('system_code', $code);
    }

    #[Scope]
    protected function failures(Builder $query): void
    {
        $query->where('outcome', 'error');
    }
}

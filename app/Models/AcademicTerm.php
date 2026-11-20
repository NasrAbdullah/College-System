<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicTerm extends Model
{
    protected $fillable = [
        'emtiaz_term_id',
        'academic_year',
        'term_number',
        'starts_on',
        'ends_on',
    ];

    protected function casts(): array
    {
        return [
            'term_number' => 'integer',
            'starts_on' => 'date',
            'ends_on' => 'date',
        ];
    }

    public function absenceRequestDetails(): HasMany
    {
        return $this->hasMany(AbsenceRequestDetail::class, 'academic_term_id');
    }

    public function isCurrent(): bool
    {
        $today = today();

        return $this->starts_on !== null
            && $this->ends_on !== null
            && $this->starts_on->lessThanOrEqualTo($today)
            && $this->ends_on->greaterThanOrEqualTo($today);
    }

    #[Scope]
    protected function current(Builder $query): void
    {
        $today = today()->toDateString();

        $query->whereDate('starts_on', '<=', $today)
            ->whereDate('ends_on', '>=', $today);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsenceRequestDetail extends Model
{
    protected $primaryKey = 'service_request_id';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'service_request_id',
        'academic_term_id',
        'starts_on',
        'ends_on',
    ];

    protected function casts(): array
    {
        return [
            'starts_on' => 'date',
            'ends_on' => 'date',
        ];
    }

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id');
    }

    public function academicTerm(): BelongsTo
    {
        return $this->belongsTo(AcademicTerm::class, 'academic_term_id');
    }

    public function spansMultipleDays(): bool
    {
        return $this->starts_on !== null
            && $this->ends_on !== null
            && ! $this->starts_on->isSameDay($this->ends_on);
    }
}

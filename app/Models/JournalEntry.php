<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = ['branch_id', 'entry_date', 'description', 'status', 'source_module'];

    protected $casts = [
        'entry_date' => 'date',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function journalLines(): HasMany
    {
        return $this->hasMany(JournalLine::class);
    }

    public function recurrencePattern(): HasOne
    {
        return $this->hasOne(RecurrencePattern::class, 'template_je_id');
    }

    public function getTotalDebitAttribute(): float
    {
        return $this->journalLines->sum('debit');
    }

    public function getTotalCreditAttribute(): float
    {
        return $this->journalLines->sum('credit');
    }
}

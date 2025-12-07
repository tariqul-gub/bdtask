<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecurrencePattern extends Model
{
    use HasFactory;

    protected $fillable = ['template_je_id', 'frequency', 'next_run_date'];

    protected $casts = [
        'next_run_date' => 'date',
    ];

    public function templateJournalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class, 'template_je_id');
    }
}

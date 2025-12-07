<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'type', 'account_group_id'];

    public function accountGroup(): BelongsTo
    {
        return $this->belongsTo(AccountGroup::class);
    }

    public function journalLines(): HasMany
    {
        return $this->hasMany(JournalLine::class);
    }

    public function openingBalances(): HasMany
    {
        return $this->hasMany(OpeningBalance::class);
    }
}

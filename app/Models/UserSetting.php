<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'setting_key',
        'setting_value',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verificationCodes(): HasMany
    {
        return $this->hasMany(VerificationCode::class, 'user_setting_id');
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_setting_id',
        'code',
        'new_setting_value',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public static function generate($userSettingId, $newSettingValue)
    {
        $verification = self::query()->create([
            'user_setting_id' => $userSettingId,
            'code' => rand(100000, 999999),
            'expires_at' => Carbon::now()->addMinutes(10),
            'new_setting_value' => $newSettingValue,
        ]);

        return $verification->code;
    }

    public function setting(): BelongsTo
    {
        return $this->belongsTo(UserSetting::class, 'user_setting_id');
    }


    public function scopeUnexpired(Builder $query, int $userId, int $settingId): Builder
    {
        return $query->whereHas('setting', function (Builder $q) use ($userId) {
            $q->where('user_id', $userId);
        })
            ->where('user_setting_id', $settingId)
            ->where('expires_at', '>', now());
    }

}

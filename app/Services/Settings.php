<?php

namespace App\Services;

use App\Enums\SettingKey;
use App\Models\Setting as SettingModel;
use Illuminate\Support\Facades\Cache;

class Settings
{
    /**
     * Retrieve a setting value by key, with optional default.
     * Cached for 60 minutes.
     */
    public static function get(SettingKey $key, mixed $default = null): mixed
    {
        $cacheKey = "settings.{$key->value}";

        return Cache::remember($cacheKey, 60, function () use ($key, $default) {
            return SettingModel::whereKey($key->value)
                ->value('value')
                          ?? $default;
        });
    }

    public static function set(SettingKey $key, mixed $value): void
    {
        SettingModel::updateOrCreate(
            ['key' => $key->value],
            ['value' => $value]
        );

        Cache::forget("settings.{$key->value}");
    }
}

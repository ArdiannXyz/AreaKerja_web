<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    /**
     * Get setting value by key with optional default fallback.
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        $setting = static::where('key', $key)->first();
        return ($setting && !empty($setting->value)) ? $setting->value : $default;
    }

    /**
     * Set or update setting value by key.
     */
    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Get full public asset URL for a header image key with fallback default.
     */
    public static function headerUrl(string $key, string $defaultPath): string
    {
        $path = static::get($key);
        if ($path && Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }
        return asset($defaultPath);
    }
}


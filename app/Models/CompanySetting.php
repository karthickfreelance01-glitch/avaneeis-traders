<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class CompanySetting extends Model
{
    use HasFactory;

    protected $fillable = ['setting_key', 'setting_value'];

    public static function valueFor(string $key, $default = null)
    {
        try {
            $setting = static::where('setting_key', $key)->first();

            return $setting ? $setting->setting_value : $default;
        } catch (Throwable $e) {
            return $default;
        }
    }

    public static function allValues(): array
    {
        try {
            return static::pluck('setting_value', 'setting_key')->toArray();
        } catch (Throwable $e) {
            return [];
        }
    }
}

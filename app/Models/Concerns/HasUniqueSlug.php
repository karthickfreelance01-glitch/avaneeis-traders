<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Gives a model a unique slug: "test", "test-2", "test-3", ...
 * Soft-deleted rows are included, because the database unique index still counts them.
 */
trait HasUniqueSlug
{
    public static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'item';

        $query = static::query();
        if (method_exists(static::class, 'bootSoftDeletes')) {
            $query->withTrashed();
        }
        if ($ignoreId) {
            // Editing: keep the current slug if it still matches the name (e.g. "test-3" for "Test")
            $current = (clone $query)->whereKey($ignoreId)->value('slug');
            if ($current && ($current === $base || preg_match('/^'.preg_quote($base, '/').'-\d+$/', $current))) {
                return $current;
            }
            $query->whereKeyNot($ignoreId);
        }

        $taken = $query->where(fn ($q) => $q->where('slug', $base)->orWhere('slug', 'like', $base.'-%'))
            ->pluck('slug')
            ->flip();

        if (! isset($taken[$base])) {
            return $base;
        }

        $n = 2;
        while (isset($taken["{$base}-{$n}"])) {
            $n++;
        }

        return "{$base}-{$n}";
    }
}

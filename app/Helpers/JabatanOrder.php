<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;

/**
 * Helper for cross-database custom ordering (SQLite and MySQL).
 * Eliminates duplicated driver-check logic across controllers.
 */
class JabatanOrder
{
    /**
     * Apply custom ordering by a predefined list of values.
     *
     * Works with both SQLite (CASE WHEN) and MySQL (FIELD()).
     *
     * @param  Builder  $query
     * @param  string   $column  The column to sort by.
     * @param  array    $order   Ordered list of values (first = highest priority).
     * @return Builder
     */
    public static function apply(Builder $query, string $column, array $order): Builder
    {
        $driver = $query->getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            $caseParts = [];
            foreach ($order as $i => $value) {
                $caseParts[] = "WHEN '{$value}' THEN {$i}";
            }
            $caseStr = implode(' ', $caseParts);

            return $query->orderByRaw("CASE {$column} {$caseStr} ELSE 999 END");
        }

        // MySQL / MariaDB
        $quoted = implode(',', array_map(fn ($v) => "'{$v}'", $order));

        return $query->orderByRaw("FIELD({$column}, {$quoted})");
    }
}

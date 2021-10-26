<?php

namespace App\Traits;

use Exception;
use Illuminate\Database\Eloquent\Builder;

trait SearchableTrait
{
    /**
     * Scope a query to search with a value
     *
     * @param Builder $query
     * @param string|null $value
     * @throws Exception
     */
    public function scopeSearch(Builder $query, ?string $value)
    {
        if (!$this->searchable) throw new Exception('Searchable property is missing from model');

        if (!$value) return;

        array_map(function ($searchable) use ($query, $value) {
            $query->orWhere($searchable, 'like', "%$value%");
        }, $this->searchable);
    }
}

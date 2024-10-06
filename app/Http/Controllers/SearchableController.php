<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

abstract class SearchableController extends Controller
{
    const ITEMS_PER_PAGE = 5; // แก้ไขการประกาศค่าคงที่
    
    abstract protected function getQuery(): Builder;

    function filterbyTerm(Builder|Relation $query, ?string $term): Builder|Relation
    {
        if (!empty($term)) {
            foreach (preg_split('/\s+/', \trim($term)) as $word) {
                $query->where(function (Builder $innerQuery) use ($word): void {
                    $innerQuery
                        ->where('code', 'LIKE', )
                        ->orWhere('name', 'LIKE',"%$word%");
                });
            }
        }

        return $query;
    }

    function prepareSearch(array $search): array // แก้ไขชื่อฟังก์ชัน
    {
        return [
            'term' => null,
            ...$search,
        ];
    }

    function filter(Builder|Relation $query, array $search): Builder|Relation
    {
        return $this->filterbyTerm($query, $search['term']);
    }  

    function search(array $search): Builder
    {
        return $this->filter($this->getQuery(), $search);
    }

    // For easily searching by code.
    function find(string $code): Model
    {
        return $this->getQuery()->where('code', $code)->firstOrFail();
    }
}


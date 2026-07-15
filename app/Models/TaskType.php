<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskType extends Model
{
    use SoftDeletes;

    protected $guarded = ["id"];

    public function scopeSearch(\Illuminate\Database\Eloquent\Builder $query, string|null $searchTerm): void
    {
        if (empty($searchTerm)) return;
        $query->where('name', 'like', "%{$searchTerm}%");
    }
}

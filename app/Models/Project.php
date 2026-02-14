<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function repositories()
    {
        return $this->hasMany(Repository::class);
    }

    public function scopeSearch(Builder $query, string|null $searchTerm): void
    {
        if (empty($searchTerm)) return;
        $query->where('name', 'like', "%{$searchTerm}%")
            ->orWhere('live_url', 'like', "%{$searchTerm}%")
            ->orWhere('demo_url', 'like', "%{$searchTerm}%");
    }
}

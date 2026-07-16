<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, "assignee_id");
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "created_by");
    }

    public function taskType(): BelongsTo
    {
        return $this->belongsTo(TaskType::class);
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->with('user', 'replies');
    }

    /**
     * Scope a query to only include popular users.
     */
    // #[Scope]
    public function scopeSearch(Builder $query, string|null $searchTerm): void
    {
        if (empty($searchTerm)) return;
        $query->where('description', 'like', "%{$searchTerm}%")
            ->orWhere("status", 'like', "%{$searchTerm}%");
    }
}

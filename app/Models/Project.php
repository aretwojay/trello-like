<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'slug',
        'owner_id',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the owner of the project.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the members of the project.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    /**
     * Get all users (owner + members) of the project.
     */
    public function allUsers()
    {
        return collect([$this->owner])->merge($this->members);
    }

    /**
     * Get the columns (categories) for the project.
     */
    public function columns(): HasMany
    {
        return $this->hasMany(ProjectColumn::class)->orderBy('order');
    }

    /**
     * Get the tasks for the project.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the categories used in this project.
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Check if user is the owner of the project.
     */
    public function isOwnedBy(User $user): bool
    {
        return $this->owner_id === $user->id;
    }

    /**
     * Check if user is a member of the project.
     */
    public function hasMember(User $user): bool
    {
        return $this->members->contains($user->id);
    }

    /**
     * Check if user has access to the project (owner or member).
     */
    public function hasAccess(User $user): bool
    {
        return $this->isOwnedBy($user) || $this->hasMember($user);
    }

    /**
     * Get tasks statistics for the project.
     */
    public function getTasksStatsAttribute(): array
    {
        $tasks = $this->tasks;
        
        return [
            'total' => $tasks->count(),
            'completed' => $tasks->where('is_completed', true)->count(),
            'overdue' => $tasks->filter(function ($task) {
                return $task->due_date && $task->due_date->isPast() && !$task->is_completed;
            })->count(),
            'due_soon' => $tasks->filter(function ($task) {
                return $task->due_date && $task->due_date->isFuture() && 
                       $task->due_date->diffInDays(now()) <= 3 && !$task->is_completed;
            })->count(),
        ];
    }

    /**
     * Get completion percentage.
     */
    public function getCompletionPercentageAttribute(): int
    {
        $stats = $this->tasks_stats;
        
        if ($stats['total'] === 0) {
            return 0;
        }
        
        return round(($stats['completed'] / $stats['total']) * 100);
    }

    /**
     * Scope to get projects where user is owner or member.
     */
    public function scopeForUser($query, User $user)
    {
        return $query->where('owner_id', $user->id)
            ->orWhereHas('members', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->name . '-' . Str::random(6));
            }
        });

        static::created(function ($project) {
            // Create default columns for new projects
            $defaultColumns = [
                ['name' => 'À faire', 'color' => '#e2e8f0', 'order' => 1, 'is_terminal' => false],
                ['name' => 'En cours', 'color' => '#fbbf24', 'order' => 2, 'is_terminal' => false],
                ['name' => 'Fait', 'color' => '#10b981', 'order' => 3, 'is_terminal' => true],
                ['name' => 'Annulé', 'color' => '#ef4444', 'order' => 4, 'is_terminal' => true],
            ];

            foreach ($defaultColumns as $column) {
                $project->columns()->create($column);
            }
        });
    }
}

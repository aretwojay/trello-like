<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'description',
        'priority',
        'due_date',
        'completed_at',
        'is_completed',
        'order',
        'project_id',
        'column_id',
        'category_id',
        'creator_id',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
        'is_completed' => 'boolean',
        'order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Priority levels.
     */
    protected $PRIORITY_LOW = 'low';
    protected $PRIORITY_MEDIUM = 'medium';
    protected $PRIORITY_HIGH = 'high';

    /**
     * Get the project that owns the task.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the column that contains the task.
     */
    public function column(): BelongsTo
    {
        return $this->belongsTo(ProjectColumn::class, 'column_id');
    }

    /**
     * Get the category of the task.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the creator of the task.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get the users assigned to the task.
     */
    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_assignments')
            ->withPivot('assigned_at')
            ->withTimestamps();
    }

    /**
     * Check if the task is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && !$this->is_completed;
    }

    /**
     * Check if the task is due soon (within 3 days).
     */
    public function isDueSoon(): bool
    {
        if (!$this->due_date || $this->is_completed) {
            return false;
        }

        return $this->due_date->isFuture() && $this->due_date->diffInDays(now()) <= 3;
    }

    /**
     * Get the priority color class.
     */
    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            $this->PRIORITY_HIGH => 'text-red-600 bg-red-100',
            $this->PRIORITY_MEDIUM => 'text-yellow-600 bg-yellow-100',
            $this->PRIORITY_LOW => 'text-green-600 bg-green-100',
            default => 'text-gray-600 bg-gray-100',
        };
    }

    /**
     * Get the priority label.
     */
    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            $this->PRIORITY_HIGH => 'Élevée',
            $this->PRIORITY_MEDIUM => 'Moyenne',
            $this->PRIORITY_LOW => 'Basse',
            default => 'Non définie',
        };
    }

    /**
     * Get the status based on column.
     */
    public function getStatusAttribute(): string
    {
        if ($this->is_completed) {
            return 'completed';
        }

        if ($this->isOverdue()) {
            return 'overdue';
        }

        if ($this->isDueSoon()) {
            return 'due_soon';
        }

        return 'active';
    }

    /**
     * Mark task as completed.
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
        ]);

        // Move to terminal column if not already there
        $terminalColumn = $this->project->columns()->terminal()->first();
        if ($terminalColumn && $this->column_id !== $terminalColumn->id) {
            $this->update(['column_id' => $terminalColumn->id]);
        }
    }

    /**
     * Mark task as incomplete.
     */
    public function markAsIncomplete(): void
    {
        $this->update([
            'is_completed' => false,
            'completed_at' => null,
        ]);
    }

    /**
     * Scopes
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->where('is_completed', false);
    }

    public function scopeDueSoon($query)
    {
        return $query->where('due_date', '>', now())
            ->where('due_date', '<=', now()->addDays(3))
            ->where('is_completed', false);
    }

    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeAssignedTo($query, User $user)
    {
        return $query->whereHas('assignedUsers', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
    }

    public function scopeCreatedBy($query, User $user)
    {
        return $query->where('creator_id', $user->id);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($task) {
            if (is_null($task->order)) {
                $task->order = static::where('column_id', $task->column_id)->max('order') + 1;
            }
        });

        static::updated(function ($task) {
            // Auto-complete task if moved to terminal column
            if ($task->isDirty('column_id') && $task->column && $task->column->is_terminal) {
                $task->markAsCompleted();
            }
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectColumn extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'project_columns';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'color',
        'order',
        'is_terminal',
        'project_id',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'order' => 'integer',
        'is_terminal' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the project that owns the column.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the tasks in this column.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'column_id')->orderBy('order');
    }

    /**
     * Get the number of tasks in this column.
     */
    public function getTaskCountAttribute(): int
    {
        return $this->tasks()->count();
    }

    /**
     * Scope to get terminal columns (completed/cancelled).
     */
    public function scopeTerminal($query)
    {
        return $query->where('is_terminal', true);
    }

    /**
     * Scope to get non-terminal columns.
     */
    public function scopeActive($query)
    {
        return $query->where('is_terminal', false);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($column) {
            if (is_null($column->order)) {
                $column->order = static::where('project_id', $column->project_id)->max('order') + 1;
            }
        });

        static::deleting(function ($column) {
            // Move tasks to the first non-terminal column when column is deleted
            $firstColumn = $column->project->columns()
                ->where('is_terminal', false)
                ->orderBy('order')
                ->first();
                
            if ($firstColumn && $firstColumn->id !== $column->id) {
                $column->tasks()->update(['column_id' => $firstColumn->id]);
            }
        });
    }
}

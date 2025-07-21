<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectColumn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ColumnController extends Controller
{
    /**
     * Store a newly created column in storage.
     */
    public function store(Request $request, Project $project)
    {
        // Check if user has access to this project
        if (!$project->hasAccess(Auth::user())) {
            abort(403, 'Vous n\'avez pas accès à ce projet.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_terminal' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $column = $project->columns()->create([
            'name' => $request->name,
            'color' => $request->color ?? '#e2e8f0',
            'is_terminal' => $request->boolean('is_terminal', false),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Colonne créée avec succès !',
                'column' => $column,
            ]);
        }

        return redirect()->back()
            ->with('success', 'Colonne créée avec succès !');
    }

    /**
     * Update the specified column in storage.
     */
    public function update(Request $request, Project $project, ProjectColumn $column)
    {
        // Check if user has access to this project
        if (!$project->hasAccess(Auth::user())) {
            abort(403, 'Vous n\'avez pas accès à ce projet.');
        }

        // Verify column belongs to the project
        if ($column->project_id !== $project->id) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_terminal' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $column->update([
            'name' => $request->name,
            'color' => $request->color ?? $column->color,
            'is_terminal' => $request->boolean('is_terminal', false),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Colonne modifiée avec succès !',
                'column' => $column,
            ]);
        }

        return redirect()->back()
            ->with('success', 'Colonne modifiée avec succès !');
    }

    /**
     * Update column order (for drag & drop reordering).
     */
    public function updateOrder(Request $request, Project $project)
    {
        // Check if user has access to this project
        if (!$project->hasAccess(Auth::user())) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'columns' => 'required|array',
            'columns.*.id' => 'required|exists:project_columns,id',
            'columns.*.order' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        foreach ($request->columns as $columnData) {
            $column = $project->columns()->findOrFail($columnData['id']);
            $column->update(['order' => $columnData['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ordre des colonnes mis à jour !',
        ]);
    }

    /**
     * Remove the specified column from storage.
     */
    public function destroy(Project $project, ProjectColumn $column)
    {
        // Check if user has access to this project
        if (!$project->hasAccess(Auth::user())) {
            abort(403);
        }

        // Verify column belongs to the project
        if ($column->project_id !== $project->id) {
            abort(404);
        }

        // Cannot delete if it's the only column
        if ($project->columns()->count() <= 1) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer la dernière colonne.');
        }

        $columnName = $column->name;
        
        // Tasks will be moved to another column by the model's boot method
        $column->delete();

        return redirect()->back()
            ->with('success', "La colonne '{$columnName}' a été supprimée.");
    }
}

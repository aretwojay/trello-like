<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request, Project $project)
    {
        // Check if user has access to this project
        if (!$project->hasAccess(Auth::user())) {
            abort(403, 'Vous n\'avez pas accès à ce projet.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,NULL,id,project_id,' . $project->id,
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $category = $project->categories()->create([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color ?? '#6b7280',
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Catégorie créée avec succès !',
                'category' => $category,
            ]);
        }

        return redirect()->back()
            ->with('success', 'Catégorie créée avec succès !');
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Project $project, Category $category)
    {
        // Check if user has access to this project
        if (!$project->hasAccess(Auth::user())) {
            abort(403, 'Vous n\'avez pas accès à ce projet.');
        }

        // Verify category belongs to the project
        if ($category->project_id !== $project->id) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id . ',id,project_id,' . $project->id,
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color ?? $category->color,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Catégorie modifiée avec succès !',
                'category' => $category,
            ]);
        }

        return redirect()->back()
            ->with('success', 'Catégorie modifiée avec succès !');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Project $project, Category $category)
    {
        // Check if user has access to this project
        if (!$project->hasAccess(Auth::user())) {
            abort(403);
        }

        // Verify category belongs to the project
        if ($category->project_id !== $project->id) {
            abort(404);
        }

        // Check if category is used by tasks
        if ($category->tasks()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer une catégorie utilisée par des tâches.');
        }

        $categoryName = $category->name;
        $category->delete();

        return redirect()->back()
            ->with('success', "La catégorie '{$categoryName}' a été supprimée.");
    }
}

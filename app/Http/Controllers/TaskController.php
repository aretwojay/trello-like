<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Show the form for creating a new task.
     */
    public function create(Request $request)
    {
        $project_id = $request->get('project_id');
        $column_id = $request->get('column_id');
        
        if (!$project_id) {
            return redirect()->route('projects.index')
                ->with('error', 'Projet requis pour créer une tâche.');
        }

        $project = Project::findOrFail($project_id);
        
        // Check if user has access to this project
        if (!$project->hasAccess(Auth::user())) {
            abort(403, 'Vous n\'avez pas accès à ce projet.');
        }

        $columns = $project->columns;
        $users = $project->allUsers();
        $categories = Auth::user()->categories;

        return view('tasks.create', compact('project', 'columns', 'users', 'categories', 'column_id'));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'priority' => 'nullable|in:low,medium,high',
            'due_date' => 'nullable|date|after:today',
            'column_id' => 'required|exists:project_columns,id',
            'category_id' => 'nullable|exists:categories,id',
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            // Rediriger vers le projet avec le modal ouvert et les erreurs
            $project = Project::find($request->project_id);
            if ($project) {
                $redirect_url = route('projects.show', $project) . '?show_modal=create_task';
                
                // Conserver la vue si elle vient de la vue liste
                if ($request->redirect_view === 'list') {
                    $redirect_url .= '&view=list';
                }
                
                if ($request->column_id) {
                    $redirect_url .= '&column_id=' . $request->column_id;
                }
                
                return redirect($redirect_url)
                    ->withErrors($validator)
                    ->withInput();
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $project = Project::findOrFail($request->project_id);

        // Check if user has access to this project
        if (!$project->hasAccess(Auth::user())) {
            abort(403, 'Vous n\'avez pas accès à ce projet.');
        }

        // Verify column belongs to the project
        $column = $project->columns()->findOrFail($request->column_id);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'project_id' => $project->id,
            'column_id' => $column->id,
            'category_id' => $request->category_id,
            'creator_id' => Auth::id(),
        ]);

        // Assign users to the task
        if ($request->assigned_users) {
            $validUserIds = $project->allUsers()->pluck('id')->toArray();
            $assignedUserIds = array_intersect($request->assigned_users, $validUserIds);
            
            if (!empty($assignedUserIds)) {
                $task->assignedUsers()->attach($assignedUserIds, [
                    'assigned_at' => now(),
                ]);
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tâche créée avec succès !',
                'task' => $task->load(['assignedUsers', 'category', 'creator']),
            ]);
        }

        // Rediriger vers la vue appropriée selon la source de la requête
        $redirectUrl = route('projects.show', $project);
        if ($request->has('redirect_view') && $request->redirect_view === 'list') {
            $redirectUrl .= '?view=list';
        }

        return redirect($redirectUrl)->with('success', 'Tâche créée avec succès !');
    }

    /**
     * Display the specified task.
     */
    public function show(Project $project, Task $task)
    {
        // Check if user has access to this project
        if (!$project->hasAccess(Auth::user())) {
            abort(403, 'Vous n\'avez pas accès à ce projet.');
        }

        // Verify task belongs to the project
        if ($task->project_id !== $project->id) {
            abort(404);
        }

        $task->load([
            'assignedUsers',
            'category',
            'creator',
            'column',
            'comments.user',
            'attachments'
        ]);

        return view('tasks.show', compact('project', 'task'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Project $project, Task $task)
    {
        // Check if user has access to this project
        if (!$project->hasAccess(Auth::user())) {
            abort(403, 'Vous n\'avez pas accès à ce projet.');
        }

        // Verify task belongs to the project
        if ($task->project_id !== $project->id) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'priority' => 'nullable|in:low,medium,high',
            'due_date' => 'nullable|date',
            'column_id' => 'required|exists:project_columns,id',
            'category_id' => 'nullable|exists:categories,id',
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify column belongs to the project
        $column = $project->columns()->findOrFail($request->column_id);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'column_id' => $column->id,
            'category_id' => $request->category_id,
        ]);

        // Update assigned users
        if ($request->has('assigned_users')) {
            $validUserIds = $project->allUsers()->pluck('id')->toArray();
            $assignedUserIds = array_intersect($request->assigned_users ?? [], $validUserIds);
            
            $task->assignedUsers()->sync($assignedUserIds);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tâche modifiée avec succès !',
                'task' => $task->load(['assignedUsers', 'category', 'creator']),
            ]);
        }

        return redirect()->back()
            ->with('success', 'Tâche modifiée avec succès !');
    }

    /**
     * Move task to different column (for Kanban drag & drop).
     */
    public function move(Request $request, Project $project, Task $task)
    {
        // Check if user has access to this project
        if (!$project->hasAccess(Auth::user())) {
            abort(403);
        }

        // Verify task belongs to the project
        if ($task->project_id !== $project->id) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'column_id' => 'required|exists:project_columns,id',
            'order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Verify column belongs to the project
        $column = $project->columns()->findOrFail($request->column_id);

        $task->update([
            'column_id' => $column->id,
            'order' => $request->order,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tâche déplacée avec succès !',
        ]);
    }

    /**
     * Toggle task completion status.
     */
    public function toggleComplete(Request $request, Project $project, Task $task)
    {
        // Check if user has access to this project
        if (!$project->hasAccess(Auth::user())) {
            abort(403);
        }

        // Verify task belongs to the project
        if ($task->project_id !== $project->id) {
            abort(404);
        }

        if ($task->is_completed) {
            $task->markAsIncomplete();
            $message = 'Tâche marquée comme incomplète.';
        } else {
            $task->markAsCompleted();
            $message = 'Tâche marquée comme terminée.';
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'is_completed' => $task->is_completed,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Project $project, Task $task)
    {
        // Check if user has access to this project
        if (!$project->hasAccess(Auth::user())) {
            abort(403);
        }

        // Verify task belongs to the project
        if ($task->project_id !== $project->id) {
            abort(404);
        }

        // Only creator or project owner can delete task
        if ($task->creator_id !== Auth::id() && !$project->isOwnedBy(Auth::user())) {
            abort(403, 'Seul le créateur de la tâche ou le propriétaire du projet peut la supprimer.');
        }

        $taskTitle = $task->title;
        $task->delete();

        return redirect()->route('projects.show', $project)
            ->with('success', "La tâche '{$taskTitle}' a été supprimée.");
    }

    /**
     * Assign user to task.
     */
    public function assignUser(Request $request, Project $project, Task $task)
    {
        // Check if user has access to this project
        if (!$project->hasAccess(Auth::user())) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::find($request->user_id);

        // Check if user is part of the project
        if (!$project->hasAccess($user)) {
            return response()->json(['error' => 'Utilisateur non membre du projet.'], 422);
        }

        // Check if already assigned
        if ($task->assignedUsers->contains($user->id)) {
            return response()->json(['error' => 'Utilisateur déjà assigné à cette tâche.'], 422);
        }

        $task->assignedUsers()->attach($user->id, ['assigned_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => "Utilisateur {$user->name} assigné à la tâche.",
            'user' => $user,
        ]);
    }

    /**
     * Remove user assignment from task.
     */
    public function unassignUser(Request $request, Project $project, Task $task)
    {
        // Check if user has access to this project
        if (!$project->hasAccess(Auth::user())) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::find($request->user_id);
        $task->assignedUsers()->detach($user->id);

        return response()->json([
            'success' => true,
            'message' => "Utilisateur {$user->name} retiré de la tâche.",
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the user's projects.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Projets possédés par l'utilisateur
        $ownedProjects = Project::where('owner_id', $user->id)
            ->withCount(['tasks', 'members'])
            ->latest()
            ->get();
        
        // Projets où l'utilisateur est membre
        $memberProjects = Project::whereHas('members', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['owner'])
            ->withCount(['tasks', 'members'])
            ->latest()
            ->get();

        return view('projects.index', compact('ownedProjects', 'memberProjects'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => Str::slug($request->name . '-' . Str::random(6)),
            'owner_id' => Auth::id(),
        ]);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Projet créé avec succès !');
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project, Request $request)
    {
        // Check if user has access to this project
        if (!$project->hasAccess(Auth::user())) {
            abort(403, 'Vous n\'avez pas accès à ce projet.');
        }
        $view = $request->get('view', 'kanban');
        
        // Load project with relationships based on view
        $project->load([
            'columns.tasks.assignedUsers',
            'columns.tasks.category',
            'columns.tasks.creator',
            'members',
            'owner',
            'categories'
        ]);

        switch ($view) {
            case 'list':
                return $this->showListView($project);
            case 'calendar':
                return $this->showCalendarView($project, $request);
            default:
                return $this->showKanbanView($project);
        }
    }

    /**
     * Show Kanban view.
     */
    private function showKanbanView(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    /**
     * Show List view.
     */
    private function showListView(Project $project)
    {
        $query = $project->tasks()->with(['assignedUsers', 'category', 'creator', 'column']);

        // Filtres de recherche
        if (request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filtre par colonne
        if (request('column')) {
            $query->where('column_id', request('column'));
        }

        // Filtre par priorité
        if (request('priority')) {
            $query->where('priority', request('priority'));
        }

        // Filtre par statut
        if (request('status')) {
            switch (request('status')) {
                case 'completed':
                    $query->where('is_completed', true);
                    break;
                case 'pending':
                    $query->where('is_completed', false);
                    break;
                case 'overdue':
                    $query->where('is_completed', false)
                          ->whereNotNull('due_date')
                          ->where('due_date', '<', now());
                    break;
            }
        }

        $tasks = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('projects.list', compact('project', 'tasks'));
    }

    /**
     * Show Calendar view.
     */
    private function showCalendarView(Project $project, Request $request)
    {
        $viewType = $request->get('calendar_view', 'month'); // day, 3days, week, month
        $date = $request->get('date', now());

        $tasks = $project->tasks()
            ->whereNotNull('due_date')
            ->with(['assignedUsers', 'category', 'creator', 'column'])
            ->get();

        return view('projects.calendar', compact('project', 'tasks', 'viewType', 'date'));
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(Project $project)
    {
        // Check if user is the owner of the project
        if (!$project->isOwnedBy(Auth::user())) {
            abort(403, 'Seul le propriétaire peut modifier ce projet.');
        }

        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, Project $project)
    {
        // Check if user is the owner of the project
        if (!$project->isOwnedBy(Auth::user())) {
            abort(403, 'Seul le propriétaire peut modifier ce projet.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $project->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Projet modifié avec succès !');
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project)
    {
        // Check if user is the owner of the project
        if (!$project->isOwnedBy(Auth::user())) {
            abort(403, 'Seul le propriétaire peut supprimer ce projet.');
        }

        $projectName = $project->name;
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', "Le projet '{$projectName}' a été supprimé.");
    }

    /**
     * Add a member to the project.
     */
    public function addMember(Request $request, Project $project)
    {
        // Check if user is the owner of the project
        if (!$project->isOwnedBy(Auth::user())) {
            abort(403, 'Seul le propriétaire peut ajouter des membres.');
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::where('email', $request->email)->first();

        // Check if user is already a member or owner
        if ($project->hasMember($user) || $project->isOwnedBy($user)) {
            return redirect()->back()
                ->with('error', 'Cet utilisateur fait déjà partie du projet.');
        }

        $project->members()->attach($user->id, [
            'role' => 'member',
            'joined_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', "Utilisateur {$user->name} ajouté au projet.");
    }

    /**
     * Remove a member from the project.
     */
    public function removeMember(Request $request, Project $project)
    {
        // Check if user is the owner of the project
        if (!$project->isOwnedBy(Auth::user())) {
            abort(403, 'Seul le propriétaire peut retirer des membres.');
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $user = User::find($request->user_id);

        // Cannot remove the owner
        if ($project->isOwnedBy($user)) {
            return redirect()->back()
                ->with('error', 'Impossible de retirer le propriétaire du projet.');
        }

        $project->members()->detach($user->id);

        // Remove user from all task assignments in this project
        $project->tasks()->each(function ($task) use ($user) {
            $task->assignedUsers()->detach($user->id);
        });

        return redirect()->back()
            ->with('success', "Utilisateur {$user->name} retiré du projet.");
    }

    /**
     * Get project statistics.
     */
    public function statistics(Project $project)
    {
        // Check if user has access to this project
        if (!$project->hasAccess(Auth::user())) {
            abort(403, 'Vous n\'avez pas accès à ce projet.');
        }

        $stats = [
            'tasks' => $project->tasks_stats,
            'members' => $project->members->count() + 1, // +1 for owner
            'categories' => $project->categories->count(),
            'columns' => $project->columns->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Duplicate a project.
     */
    public function duplicate(Project $project)
    {
        // Check if user has access to this project
        if (!$project->hasAccess(Auth::user())) {
            abort(403, 'Vous n\'avez pas accès à ce projet.');
        }

        $newProject = Project::create([
            'name' => $project->name . ' (Copie)',
            'description' => $project->description,
            'slug' => Str::slug($project->name . '-copie-' . Str::random(6)),
            'owner_id' => Auth::id(),
        ]);

        // Duplicate categories
        foreach ($project->categories as $category) {
            $newProject->categories()->create([
                'name' => $category->name,
                'description' => $category->description,
                'color' => $category->color,
            ]);
        }

        // Note: Columns are automatically created by the Project model boot method

        return redirect()->route('projects.show', $newProject)
            ->with('success', 'Projet dupliqué avec succès !');
    }
}

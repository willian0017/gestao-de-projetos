<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProjectController extends Controller
{
    /**
     * Listagem.
     */
    public function index()
    {
        $user = Auth::user();

        $projects = Project::where('user_id', $user->id)
            ->orWhereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with('creator', 'users')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Projects/Index', [
            'projects' => $projects,
            'users' => User::select('id', 'name')->get(),
        ]);
    }

    /**
     * Formulário de criação.
     */
    public function create()
    {
        return Inertia::render('Projects/Create', [
            'users' => User::select('id', 'name')->get(),
        ]);
    }

    /**
     * Armazena um novo projeto no banco de dados.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'phase' => 'required|string|max:255',
            'team_members' => 'nullable|array',
            'team_members.*' => 'exists:users,id',
        ]);

        $project = Auth::user()->createdProjects()->create([
            'title' => $validated['title'],
            'client_name' => $validated['client_name'],
            'phase' => $validated['phase'],
        ]);

        if (!empty($validated['team_members'])) {
            $project->users()->attach($validated['team_members']);
        }

        return redirect()->route('projects.index')->with('success', 'Projeto criado com sucesso!');
    }

    /**
     * Formulário de edição.
     */
    public function edit(Project $project)
    {
        if (Auth::user()->id !== $project->user_id && ! $project->users->contains(Auth::user()->id)) {
            abort(403);
        }

        $project->load('users');

        return Inertia::render('Projects/Edit', [
            'project' => $project,
            'users' => User::select('id', 'name')->get(),
            'selectedTeamMembers' => $project->users->pluck('id')->toArray(),
        ]);
    }

    /**
     * Atualiza o projeto.
     */
    public function update(Request $request, Project $project)
    {
        if (Auth::user()->id !== $project->user_id && ! $project->users->contains(Auth::user()->id)) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'phase' => 'required|string|max:255',
            'team_members' => 'nullable|array',
            'team_members.*' => 'exists:users,id',
            'is_active' => 'required|boolean',
        ]);

        $project->update($validated);

        $project->users()->sync($validated['team_members'] ?? []);

        return redirect()->route('projects.index')->with('success', 'Projeto atualizado com sucesso!');
    }

    /**
     * Desativa um projeto.
     */
    public function destroy(Project $project)
    {
        if (Auth::user()->id !== $project->user_id) {
            abort(403);
        }

        $project->update(['is_active' => false]);

        return redirect()->route('projects.index')->with('success', 'Projeto desativado com sucesso!');
    }
}

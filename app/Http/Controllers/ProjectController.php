<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $projects = Project::with('department')
            ->withCount([
                'tasks',
                'tasks as done_tasks_count' => function ($query) {
                    $query->where('status', Task::STATUS_DONE);
                },
            ])
            ->when($user->isHead(), function ($query) use ($user) {
                $query->where('department_id', $user->department_id);
            })
            ->orderByRaw("status = 'completed'")
            ->orderBy('deadline')
            ->get();

        return view('projects.index', compact('projects'));
    }

    public function show(Project $project): View
    {
        $this->ensureCanView($project);

        $project->load(['department.head', 'creator']);

        $tasks = $project->tasks()
            ->with('assignee')
            ->orderBy('deadline')
            ->get();

        $statusCounts = $project->tasks()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('projects.show', compact('project', 'tasks', 'statusCounts'));
    }

    public function create(): View|RedirectResponse
    {
        $departments = $this->departmentsWithHead();

        if ($departments->isEmpty()) {
            return redirect()
                ->route('employees.index')
                ->with('error', 'Appoint a Department Head first. A project can only be assigned to a department that has a Head.');
        }

        return view('projects.create', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'department_id' => ['required', Rule::exists('departments', 'id')->whereNotNull('head_id')],
            'deadline' => ['required', 'date', 'after_or_equal:today'],
        ]);

        Project::create([
            ...$data,
            'created_by' => Auth::id(),
            'status' => Project::STATUS_ACTIVE,
        ]);

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function edit(Project $project): View
    {
        $departments = $this->departmentsWithHead();
        $hasTasks = $project->tasks()->exists();
        $project->load('department');

        return view('projects.edit', compact('project', 'departments', 'hasTasks'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $hasTasks = $project->tasks()->exists();

        $rules = [
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'deadline' => ['required', 'date'],
        ];

        // Task thakle department bodlano jabe na
        if (! $hasTasks) {
            $rules['department_id'] = ['required', Rule::exists('departments', 'id')->whereNotNull('head_id')];
        }

        $data = $request->validate($rules);

        $project->update($data);

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Project updated successfully.');
    }

    // Jei department e Head ache sudhu sei department gulo
    private function departmentsWithHead(): Collection
    {
        return Department::whereNotNull('head_id')->orderBy('name')->get();
    }

    // Head sudhu nijer department er project dekhte parbe
    private function ensureCanView(Project $project): void
    {
        $user = Auth::user();

        abort_if($user->isHead() && (int) $project->department_id !== (int) $user->department_id, 403);
    }
}
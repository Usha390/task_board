<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\WorkloadFlag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    // /dashboard e ashle role dekhe sothik dashboard e pathiye dey
    public function index(): RedirectResponse
    {
        $user = Auth::user();

        if ($user->isDirector()) {
            return redirect()->route('director.dashboard');
        }

        if ($user->isHead()) {
            return redirect()->route('head.dashboard');
        }

        return redirect()->route('employee.dashboard');
    }

    public function director(): View
    {
        $stats = [
            'departments' => Department::count(),
            'employees' => User::where('role', User::ROLE_EMPLOYEE)->where('is_active', true)->count(),
            'projects' => Project::where('status', Project::STATUS_ACTIVE)->count(),
            'overdue' => Task::where('status', '!=', Task::STATUS_DONE)
                ->whereDate('deadline', '<', today())
                ->count(),
        ];

        $statusCounts = Task::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $atRisk = WorkloadFlag::with('user.department')
            ->where('is_at_risk', true)
            ->get();

        $departments = Department::with('head')
            ->withCount(['employees', 'projects'])
            ->orderBy('name')
            ->get();

        return view('dashboards.director', compact('stats', 'statusCounts', 'atRisk', 'departments'));
    }

    public function head(): View
    {
        $user = Auth::user();
        $departmentId = $user->department_id;

        // Ei department er shob task (project er department diye)
        $departmentTasks = function () use ($departmentId) {
            return Task::whereHas('project', function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            });
        };

        $statusCounts = $departmentTasks()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $stats = [
            'team' => User::where('department_id', $departmentId)
                ->where('role', User::ROLE_EMPLOYEE)
                ->where('is_active', true)
                ->count(),
            'projects' => Project::where('department_id', $departmentId)
                ->where('status', Project::STATUS_ACTIVE)
                ->count(),
            'review' => (int) $statusCounts->get(Task::STATUS_REVIEW, 0),
            'overdue' => $departmentTasks()
                ->where('status', '!=', Task::STATUS_DONE)
                ->whereDate('deadline', '<', today())
                ->count(),
        ];

        $reviewQueue = $departmentTasks()
            ->with(['assignee', 'project'])
            ->where('status', Task::STATUS_REVIEW)
            ->orderBy('submitted_at')
            ->limit(5)
            ->get();

        $atRisk = WorkloadFlag::with('user.department')
            ->where('is_at_risk', true)
            ->whereHas('user', function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            })
            ->get();

        $subtitle = $user->department
            ? "Keep the {$user->department->name} team's work moving and reviews on time."
            : "Keep your team's work moving and reviews on time.";

        return view('dashboards.head', compact('stats', 'statusCounts', 'reviewQueue', 'atRisk', 'subtitle'));
    }

    public function employee(): View
    {
        $user = Auth::user();

        $statusCounts = Task::where('assignee_id', $user->id)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $overdue = Task::where('assignee_id', $user->id)
            ->where('status', '!=', Task::STATUS_DONE)
            ->whereDate('deadline', '<', today())
            ->count();

        $upcoming = Task::with('project')
            ->where('assignee_id', $user->id)
            ->where('status', '!=', Task::STATUS_DONE)
            ->orderBy('deadline')
            ->limit(5)
            ->get();

        $flag = $user->workloadFlag;

        $pending = (int) $statusCounts->get(Task::STATUS_TODO, 0)
            + (int) $statusCounts->get(Task::STATUS_IN_PROGRESS, 0);

        $subtitle = $pending === 0
            ? 'You have no pending tasks right now.'
            : "You have {$pending} " . ($pending === 1 ? 'task' : 'tasks') . ' to work on.';

        return view('dashboards.employee', compact('statusCounts', 'overdue', 'upcoming', 'flag', 'subtitle'));
    }
}
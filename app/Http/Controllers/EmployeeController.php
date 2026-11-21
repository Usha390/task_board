<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(): View
    {
        $employees = User::with('department.head')
            ->whereIn('role', [User::ROLE_EMPLOYEE, User::ROLE_HEAD])
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->get();

        return view('employees.index', compact('employees'));
    }

    public function create(): View|RedirectResponse
    {
        $departments = Department::orderBy('name')->get();

        if ($departments->isEmpty()) {
            return redirect()
                ->route('departments.create')
                ->with('error', 'Create a department first, then add employees.');
        }

        return view('employees.create', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:191', 'unique:users,email'],
            'department_id' => ['required', 'exists:departments,id'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            ...$data,
            'role' => User::ROLE_EMPLOYEE,
            'is_active' => true,
        ]);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee added successfully.');
    }

    public function edit(User $user): View
    {
        $this->ensureManageable($user);

        $user->load('department');
        $departments = Department::orderBy('name')->get();

        return view('employees.edit', [
            'employee' => $user,
            'departments' => $departments,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->ensureManageable($user);

        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:191', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];

        // Head er department ekhane bodlano jabe na
        if (! $user->isHead()) {
            $rules['department_id'] = ['required', 'exists:departments,id'];
        }

        $data = $request->validate($rules);

        // Department bodlate gele unfinished task thakle atkabo
        if (! $user->isHead() && (int) $data['department_id'] !== (int) $user->department_id) {
            $openTasks = $user->tasks()->where('status', '!=', Task::STATUS_DONE)->count();

            if ($openTasks > 0) {
                return back()
                    ->withErrors(['department_id' => "This employee still has {$openTasks} unfinished task(s). Finish or reassign them before changing the department."])
                    ->withInput();
            }
        }

        // Password faka thakle purono password i thakbe
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        $this->ensureManageable($user);

        if ($user->is_active) {
            if ($user->isHead()) {
                return back()->with('error', 'Appoint another Department Head before deactivating the current Head.');
            }

            $openTasks = $user->tasks()->where('status', '!=', Task::STATUS_DONE)->count();

            if ($openTasks > 0) {
                return back()->with('error', "{$user->name} still has {$openTasks} unfinished task(s). Finish or reassign them first.");
            }
        }

        $user->update(['is_active' => ! $user->is_active]);

        return back()->with(
            'success',
            $user->is_active ? "{$user->name} is active again." : "{$user->name} has been deactivated."
        );
    }

    public function appointHead(User $user): RedirectResponse
    {
        $this->ensureManageable($user);

        if (! $user->isEmployee() || ! $user->is_active || ! $user->department_id) {
            return back()->with('error', 'Only an active employee with a department can be appointed as Head.');
        }

        DB::transaction(function () use ($user) {
            $department = Department::lockForUpdate()->findOrFail($user->department_id);

            // Ager Head ke abar Employee banai
            if ($department->head_id && (int) $department->head_id !== (int) $user->id) {
                User::whereKey($department->head_id)->update(['role' => User::ROLE_EMPLOYEE]);
            }

            $department->update(['head_id' => $user->id]);
            $user->update(['role' => User::ROLE_HEAD]);
        });

        return back()->with('success', "{$user->name} is now the Head of {$user->department->name}.");
    }

    // Director er account ei page theke change kora jabe na
    private function ensureManageable(User $user): void
    {
        abort_if($user->isDirector(), 404);
    }
}
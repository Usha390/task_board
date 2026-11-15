<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\WorkloadFlag;
use Illuminate\Database\Seeder;

class DashboardDemoSeeder extends Seeder
{
    public function run(): void
    {
        $director = User::where('role', User::ROLE_DIRECTOR)->firstOrFail();
        $head = User::where('role', User::ROLE_HEAD)->firstOrFail();
        $employee = User::where('role', User::ROLE_EMPLOYEE)->firstOrFail();

        $project = Project::firstOrCreate(
            ['title' => 'Website Redesign', 'department_id' => $head->department_id],
            [
                'description' => 'Demo project for dashboard preview.',
                'created_by' => $director->id,
                'deadline' => now()->addDays(30)->toDateString(),
                'status' => Project::STATUS_ACTIVE,
            ]
        );

        $tasks = [
            ['Design homepage', Task::STATUS_TODO, Task::PRIORITY_HIGH, 2],
            ['Write page copy', Task::STATUS_IN_PROGRESS, Task::PRIORITY_MEDIUM, 5],
            ['Fix navbar bug', Task::STATUS_REVIEW, Task::PRIORITY_LOW, -1],
            ['Update logo', Task::STATUS_DONE, Task::PRIORITY_LOW, -3],
            ['Prepare launch report', Task::STATUS_TODO, Task::PRIORITY_HIGH, 1],
        ];

        foreach ($tasks as [$title, $status, $priority, $days]) {
            Task::firstOrCreate(
                ['project_id' => $project->id, 'title' => $title],
                [
                    'assignee_id' => $employee->id,
                    'created_by' => $head->id,
                    'priority' => $priority,
                    'deadline' => now()->addDays($days)->toDateString(),
                    'status' => $status,
                    'submitted_at' => $status === Task::STATUS_REVIEW ? now() : null,
                    'submitted_by' => $status === Task::STATUS_REVIEW ? $employee->id : null,
                ]
            );
        }

        // Fake flag, Phase 12 e asol Burnout Detector eta automatic banabe
        WorkloadFlag::updateOrCreate(
            ['user_id' => $employee->id],
            ['pending_count' => 5, 'due_soon_count' => 3, 'is_at_risk' => true]
        );
    }
}
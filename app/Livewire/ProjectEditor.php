<?php

namespace App\Livewire;

use App\Enums\RoleEnum;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ProjectEditor extends Component
{
    public ?Project $project = null;

    public string $name = '';

    public string $description = '';

    public string $startDate = '';

    public ?string $endDate = null;

    public ?int $ownerId = null;

    public function mount(?int $projectID = null): void
    {
        if ($projectID) {
            $this->project = Project::findOrFail($projectID);
            $this->name = $this->project->name;
            $this->description = $this->project->description ?? '';
            $this->startDate = $this->project->start_date?->format('Y-m-d') ?? '';
            $this->endDate = $this->project->end_date?->format('Y-m-d') ?? '';
            $this->ownerId = $this->project->user_id;
        } else {
            $this->project = new Project;
        }
    }

    protected function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'startDate' => ['required', 'date'],
            'endDate' => ['nullable', 'date', 'after_or_equal:startDate'],
        ];

        if ($this->isAdmin() && $this->project?->exists) {
            $rules['ownerId'] = ['required', 'integer', 'exists:users,id'];
        }

        return $rules;
    }

    public function save(): void
    {
        $this->validate();

        $this->project->name = $this->name;
        $this->project->description = $this->description ?: null;
        $this->project->start_date = $this->startDate;
        $this->project->end_date = $this->endDate ?: null;

        if (! $this->project->exists) {
            $this->project->user_id = Auth::id();
        } elseif ($this->isAdmin() && $this->ownerId) {
            $this->project->user_id = $this->ownerId;
        }

        $this->project->save();

        session()->flash('success', 'Projekt uložen.');

        $this->redirect(route('dashboard'), navigate: true);
    }

    public function render()
    {
        $managers = $this->isAdmin()
            ? User::whereIn('role', [RoleEnum::Manager->value, RoleEnum::Admin->value])
                ->orderBy('name')
                ->get(['id', 'name', 'email'])
            : collect();

        return view('livewire.project-editor', ['managers' => $managers]);
    }

    private function isAdmin(): bool
    {
        return Auth::user()?->role === RoleEnum::Admin;
    }
}

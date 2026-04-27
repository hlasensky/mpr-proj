<?php

namespace App\Livewire;

use App\Models\Project;
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

    public string $endDate = '';

    public function mount(?int $projectID = null): void
    {
        if ($projectID) {
            $this->project = Project::findOrFail($projectID);
            $this->name = $this->project->name;
            $this->description = $this->project->description ?? '';
            $this->startDate = $this->project->start_date?->format('Y-m-d') ?? '';
            $this->endDate = $this->project->end_date?->format('Y-m-d') ?? '';
        } else {
            $this->project = new Project;
        }
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'startDate' => ['nullable', 'date'],
            'endDate' => ['nullable', 'date', 'after_or_equal:startDate'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->project->name = $this->name;
        $this->project->description = $this->description ?: null;
        $this->project->start_date = $this->startDate ?: null;
        $this->project->end_date = $this->endDate ?: null;

        if (! $this->project->exists) {
            $this->project->user_id = Auth::id();
        }

        $this->project->save();

        session()->flash('success', 'Projekt uložen.');

        $this->redirect(route('dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.project-editor');
    }
}

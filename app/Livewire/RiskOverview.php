<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Risk;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class RiskOverview extends Component
{
    public Project $project;

    public Collection $risks;

    public string $matrixView = 'grid';

    public function mount(int $projectID): void
    {
        $this->project = Project::findOrFail($projectID);
        $this->risks = $this->project->risks()->get();
    }

    public function deleteRisk(int $riskID): void
    {
        Risk::destroy($riskID);
        $this->risks = $this->project->risks()->get();
    }

    public function deleteProject(): void
    {
        $this->project->delete();

        $this->redirect(route('dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.risk-overview');
    }
}

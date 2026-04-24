<?php

namespace App\Livewire;

use App\Enums\RoleEnum;
use App\Models\Project;
use App\Models\Risk;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RiskOverview extends Component
{
    public Project $project;
    public Collection $risks;
    protected $passedID;
    public function mount($projectID)
    {
        $this->passedID = $projectID;
        $this->load();
    }
    public function load()
    {
        $this->project = Project::findOrFail($this->passedID);
        $this->risks = $this->project->risks;

    }
    public function delete($riskID)
    {
        Risk::destroy($riskID);
        $this->load();
    }
    public function render()
    {
        return view('livewire.risk-overview')->layout('layouts.app');
    }
}

<?php

namespace App\Livewire;

use App\Enums\RoleEnum;
use App\Models\Project;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProjectOverview extends Component
{
    public Collection $projects;
    public function mount()
    {
        $this->load();
    }
    public function load()
    {
        $user = Auth::user();
        if($user->role == RoleEnum::Admin) {
            $this->projects = Project::all();
        }else{
            $this->projects = $user->project;
        }
    }
    public function delete($projectID)
    {
        Project::destroy($projectID);
        $this->load();
    }
    public function render()
    {
        return view('livewire.project-overview')->layout('layouts.app');
    }
}

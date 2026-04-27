<?php

namespace App\Livewire;

use App\Enums\RoleEnum;
use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ProjectOverview extends Component
{
    public Collection $projects;

    public function mount(): void
    {
        $this->load();
    }

    public function load(): void
    {
        $user = Auth::user();

        if ($user->role === RoleEnum::Admin) {
            $this->projects = Project::withCount('risks')->with('user')->get();
        } else {
            $this->projects = $user->projects()->withCount('risks')->get();
        }
    }

    public function delete(int $projectID): void
    {
        Project::destroy($projectID);
        $this->load();
    }

    public function render()
    {
        return view('livewire.project-overview');
    }
}

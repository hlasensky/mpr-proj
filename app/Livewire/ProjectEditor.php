<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;

class ProjectEditor extends Component
{
    public Project $project;
    public string $name;
//    ....
    protected $rules = [
        'name' => 'required|string',
        //    ....
    ];

    public function mount($projectID = null)
    {
        if($projectID){
            $this->project = Project::findOrFail($projectID);
        }else{
            $this->project = new Project();
        }

    }
    public function save(){
        $this->validate();
//        todo
        $this->project->save();
    }

    public function render()
    {
        return view('livewire.project-editor')->layout('layouts.app');
    }
}

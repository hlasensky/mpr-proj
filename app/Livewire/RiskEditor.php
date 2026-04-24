<?php

namespace App\Livewire;

use App\Models\Risk;
use Livewire\Component;

class RiskEditor extends Component
{
    public Risk $risk;
    public string $name;
//    ....
    protected $rules = [
        'name' => 'required|string',
        //    ....
    ];
    public function mount($riskID = null)
    {
        if($riskID){
            $this->risk = Risk::findOrFail($riskID);
        }else{
            $this->risk = new Risk();
        }
    }
    public function save(){
        $this->validate();
//        todo
        $this->risk->save();
    }
    public function render()
    {
        return view('livewire.risk-editor')->layout('layouts.app');
    }
}

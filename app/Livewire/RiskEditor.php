<?php

namespace App\Livewire;

use App\Models\Risk;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class RiskEditor extends Component
{
    public ?Risk $risk = null;

    public int $projectID = 0;

    public string $name = '';

    public int $impact = 1;

    public int $likelihood = 1;

    public function mount(int $projectID, ?int $riskID = null): void
    {
        $this->projectID = $projectID;

        if ($riskID) {
            $this->risk = Risk::findOrFail($riskID);
            $this->name = $this->risk->name;
            $this->impact = $this->risk->impact;
            $this->likelihood = $this->risk->likelihood;
        } else {
            $this->risk = new Risk;
        }
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'impact' => ['required', 'integer', 'min:1', 'max:10'],
            'likelihood' => ['required', 'integer', 'min:1', 'max:10'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->risk->name = $this->name;
        $this->risk->impact = $this->impact;
        $this->risk->likelihood = $this->likelihood;

        if (! $this->risk->exists) {
            $this->risk->project_id = $this->projectID;
            $this->risk->user_id = Auth::id();
        }

        $this->risk->save();

        session()->flash('success', 'Riziko uloženo.');

        $this->redirect(url()->previous(), navigate: true);
    }

    public function render()
    {
        return view('livewire.risk-editor');
    }
}

<?php

namespace App\Livewire;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class UserEditor extends Component
{
    public ?User $user = null;

    public string $name = '';

    public string $email = '';

    public string $role = '';

    public function mount(?int $userID = null): void
    {
        if ($userID) {
            $this->user = User::findOrFail($userID);
            $this->name = $this->user->name;
            $this->email = $this->user->email;
            $this->role = $this->user->role->value;
        } else {
            $this->user = new User;
            $this->role = RoleEnum::Manager->value;
        }
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user?->id)],
            'role' => ['required', Rule::enum(RoleEnum::class)],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();
        $validated['role'] = RoleEnum::from($validated['role']);
        $this->user->fill($validated);
        $this->user->save();

        session()->flash('success', 'Uživatel uložen.');

        $this->redirect(route('user.overview'), navigate: true);
    }

    public function render()
    {
        return view('livewire.user-editor');
    }
}

<?php

namespace App\Livewire;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class UserOverview extends Component
{
    public function verify(int $id): void
    {
        $user = User::findOrFail($id);
        $user->role = RoleEnum::Manager;
        $user->save();

        session()->flash('success', "Uživatel {$user->name} byl ověřen.");
    }

    public function delete(int $id): void
    {
        if (Auth::id() === $id) {
            session()->flash('error', 'Nemůžete smazat sami sebe.');

            return;
        }

        User::destroy($id);

        session()->flash('success', 'Uživatel smazán.');
    }

    public function render()
    {
        return view('livewire.user-overview', [
            'unverifiedUsers' => User::where('role', RoleEnum::UnVerified->value)->get(),
            'verifiedUsers' => User::where('role', '!=', RoleEnum::UnVerified->value)->get(),
        ]);
    }
}

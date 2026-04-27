<?php

use App\Livewire\ProjectEditor;
use App\Livewire\ProjectOverview;
use App\Livewire\RiskEditor;
use App\Livewire\RiskOverview;
use App\Livewire\UserEditor;
use App\Livewire\UserOverview;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified', 'role_auth'])->group(function () {
    Route::group(['prefix' => '/project'], function () {
        Route::get('overview', ProjectOverview::class)->name('dashboard');
        Route::get('editor/{projectID?}', ProjectEditor::class)->name('project.editor');
    });

    Route::group(['prefix' => '/risk'], function () {
        Route::get('overview/{projectID}', RiskOverview::class)->name('risk.overview');
        Route::get('editor/{projectID}/{riskID?}', RiskEditor::class)->name('risk.editor');
    });

    Route::group(['prefix' => '/users'], function () {
        Route::get('overview', UserOverview::class)->name('user.overview');
        Route::get('editor/{userID?}', UserEditor::class)->name('user.editor');
    });
});

Route::get('please-wait-for-verification', function () {
    return view('please-wait-for-verification');
})->name('please_wait_for_verification');

require __DIR__.'/settings.php';

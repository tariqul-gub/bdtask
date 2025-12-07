<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\AccountGroupController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\ReportingController;

Route::get('/', function () {
    return redirect()->route('home');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    Route::resource('branches', BranchController::class);
    
    Route::resource('account-groups', AccountGroupController::class);
    
    Route::resource('accounts', AccountController::class);
    
    Route::resource('journal-entries', JournalEntryController::class);
    Route::post('journal-entries/{journal_entry}/approve', [JournalEntryController::class, 'approve'])
        ->name('journal-entries.approve');
    
    Route::get('reports', [ReportingController::class, 'index'])->name('reports.index');
    Route::get('reports/trial-balance', [ReportingController::class, 'trialBalance'])->name('reports.trial-balance');
});

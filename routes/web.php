<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\UserElectionController;
use App\Http\Controllers\UserVoteController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Routes for displaying elections, positions, and candidates
        Route::get('/elections', [UserElectionController::class, 'index'])->name('elections.index');
        Route::get('/elections/{id}', [UserElectionController::class, 'show'])->name('user-elections.show');
    
        // Routes for voting
        Route::post('/elections/{election}/vote', [UserVoteController::class, 'store'])->name('votes.store');
    
        // Add authentication routes if not already included
});

// Election Routes
Route::middleware('auth')->prefix('admin')->group(function () {


    // Election Routes
    Route::resource('elections', ElectionController::class);

    // Position Routes
    Route::resource('positions', PositionController::class);

    // Candidate Routes
    Route::resource('candidates', CandidateController::class);

    Route::get('/elections/{election}/positions', [ElectionController::class, 'getPositions']);

    Route::get('/elections/{election_id}/votes', [ElectionController::class, 'calculateVotes'])->name('admin.elections.votes');



});


require __DIR__.'/auth.php';

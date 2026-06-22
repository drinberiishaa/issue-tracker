<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\IssueMemberController;
use App\Http\Controllers\IssueTagController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public / read routes
|--------------------------------------------------------------------------
| Browsing projects, issues, and tags is open to anyone so the tracker is
| viewable without requiring an account. Mutating actions are gated by
| auth middleware below (and by ProjectPolicy for project ownership).
*/

Route::get('/', function () {
    return redirect()->route('projects.index');
});

Route::resource('projects', ProjectController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
Route::resource('issues', IssueController::class)->only(['index', 'show']);
Route::get('issues/search/ajax', [IssueController::class, 'search'])->name('issues.search');
Route::get('tags', [TagController::class, 'index'])->name('tags.index');

// Read-only comment listing (paginated AJAX) is open to anyone viewing an issue.
Route::get('issues/{issue}/comments', [CommentController::class, 'index'])->name('issues.comments.index');

/*
|--------------------------------------------------------------------------
| Authenticated routes
|--------------------------------------------------------------------------
| Creating/editing/deleting projects, issues, tags, comments, and managing
| tag/member attachments all require an authenticated user.
*/

Route::middleware('auth')->group(function () {
    Route::resource('projects', ProjectController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('issues', IssueController::class)->except(['index', 'show']);
    Route::post('tags', [TagController::class, 'store'])->name('tags.store');

    // Attach/detach tags on an issue via AJAX.
    Route::post('issues/{issue}/tags', [IssueTagController::class, 'store'])->name('issues.tags.store');
    Route::delete('issues/{issue}/tags/{tag}', [IssueTagController::class, 'destroy'])->name('issues.tags.destroy');

    // Attach/detach members (users) on an issue via AJAX (bonus).
    Route::post('issues/{issue}/members', [IssueMemberController::class, 'store'])->name('issues.members.store');
    Route::delete('issues/{issue}/members/{user}', [IssueMemberController::class, 'destroy'])->name('issues.members.destroy');

    // Add a comment via AJAX.
    Route::post('issues/{issue}/comments', [CommentController::class, 'store'])->name('issues.comments.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

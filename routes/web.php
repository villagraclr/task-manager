<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::livewire('/projects', 'pages::projects.index')->name('projects.index');
    Route::livewire('/projects/create', 'pages::projects.create')->name('projects.create');
    Route::livewire('/projects/{project}/tasks/create', 'pages::tasks.create')->name('tasks.create');
    Route::livewire('/projects/{project}', 'pages::projects.show')->name('projects.show');
    Route::livewire('/projects/{project}/edit', 'pages::projects.edit')->name('projects.edit');
    Route::livewire('/projects/{project}/members', 'pages::projects.members')->name('projects.members');

    Route::livewire('/tasks/{task}', 'pages::tasks.show')->name('tasks.show');
    Route::livewire('/tasks/{task}/edit', 'pages::tasks.edit')->name('tasks.edit');
});

require __DIR__.'/settings.php';

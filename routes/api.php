<?php

use App\Http\Controllers\JobApiController;
use Illuminate\Support\Facades\Route;

Route::get('/jobs', [JobApiController::class, 'index'])->name('api.jobs.index');

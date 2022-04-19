<?php

use Illuminate\Support\Facades\Route;
use Phuclh\QuickEdit\Http\Controllers\UpdateController;

Route::post('update/{resource}', UpdateController::class);

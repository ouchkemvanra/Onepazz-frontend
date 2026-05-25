<?php

use App\Http\Controllers\Api\CheckinController;
use Illuminate\Support\Facades\Route;

Route::post('/checkin', [CheckinController::class, 'store']);

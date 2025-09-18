<?php

use App\Http\Controllers\UserUploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/import-csv', [UserUploadController::class, 'store']);

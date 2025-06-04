<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/reports', [ReportController::class, 'getAll']);
Route::get('/reports/{id}', [ReportController::class, 'getById']);
Route::post('/reports', [ReportController::class, 'create']);
Route::get('/reports/{id}/download', [ReportController::class, 'downloadFile'])
    ->name('reports.download');
Route::get('/reports/{id}/preview-image', [ReportController::class, 'getPdfAsImage']);


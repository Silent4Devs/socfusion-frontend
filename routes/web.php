<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatHistoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return redirect('/admin', 301);
});

Route::get('/preview-email', function () {
    $data = session('email_preview_data', []); // default empty array

    if (empty($data)) {
        abort(404, 'No preview data found.');
    }
    return new App\Mail\ReportEmail($data['emailSubject'],$data['alarm'], $data['csvData'], $data['ips'], $data['urls'], $data['report'], $data['details']);
});

<?php

use Illuminate\Support\Facades\Route;
use Kreait\Firebase\Messaging\CloudMessage;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    try {
        $messaging = app('firebase.messaging');
        $content = [
            'title' => 'تم الحجز بنجاح',
            'body' => 'شكرًا لاختيارك فندقنا!'
        ];

        $message = CloudMessage::withTarget('token', 'fNIW0Jfpxa7gTqMo03FTHJ:APA91bFGv4WItHuoktjsqiAD4OymU6TcUcNuvZ49x2bh_-IOGiGBaz0FP12saOTk9ExBWqsWXZY4IhEDss0YMncpCosbbJ7aQ8f_cXcGdfpy-kPDXH_-C1s')
            ->withNotification($content);
        $messaging->send($message);

        dd($message);

    } catch (\Exception $e) {
        \Log::error('فشل إرسال الإشعار: ' . $e->getMessage());

    }});

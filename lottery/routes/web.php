<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LotteryController;




Route::get('/', function () {
    return view('main');
});


Route::post('/lotteries/randomize', [LotteryController::class, 'randomize']);

Route::fallback(function () {
    return view('notfound');
});

<?php

// PLEASE DEAR DOG DON'T FORGET TO DELETE WHEN PUBLISH THANKS

use App\Http\Controllers\OrderController;

Route::get('/testAPI', function() {
    $statuses = OrderController::getPossibleStatus();

    return view('dev.DEVTEST', ["statuses" => $statuses]);
});

Route::get('/testPOST', function() {
    return view('dev.DEVTESTPOST');
});

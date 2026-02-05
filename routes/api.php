<?php

use App\Http\Controllers\RegisterAdminController;
use Illuminate\Support\Facades\Route;



   

Route::get('/test', function() {
    return response()->json(['status' => 'ok']);
});

?>
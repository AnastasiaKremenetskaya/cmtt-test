<?php
/**
 * This file contains all the routes for the project
 */

use App\Http\Controllers\AdController;
use Pecee\SimpleRouter\SimpleRouter;

// API
SimpleRouter::group(['prefix' => '/ads'], function () {
    // Создание объявления
    SimpleRouter::post('', [AdController::class, 'store']);

    // Открутка объявления
    SimpleRouter::get('/relevant', [AdController::class, 'relevant']);

    // Редактирование объявления
    SimpleRouter::put('/{id}', [AdController::class, 'update'])
        ->where([ 'id' => '[0-9]+' ]);
});

SimpleRouter::error(function() {
    return response()->httpCode(404);
});

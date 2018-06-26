<?php
use App\Analyser\Checker;
use App\Http\Requests\CheckRequest;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/check', function (CheckRequest $request, Checker $checker) {
    $results = $checker->check($request->url);

    return response()->json(iterator_to_array($results));
})->name('check');



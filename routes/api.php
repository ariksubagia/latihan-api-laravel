<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('/article', [ ArticleController::class, 'list' ]);
Route::post('/article', [ ArticleController::class, 'create' ]);
Route::get('/article/{id}', [ ArticleController::class, 'retrieve' ]);
Route::post('/article/{id}', [ ArticleController::class, 'update' ]);
Route::delete('/article/{id}', [ ArticleController::class, 'delete' ]);
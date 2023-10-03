<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controller\ChatController;

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

Route::group(['middleware'=>'api'],function($routes){
    Route::post('/chat/start-conversation', [ChatController::class, 'startConversation']);
    Route::get('/chat/conversations', [ChatController::class, 'getConversations']);
    Route::get('/chat/conversations/{conversation}', [ChatController::class, 'getMessages']);
    Route::post('/chat/conversations/{conversation}/messages', [ChatController::class, 'sendMessage']);















});
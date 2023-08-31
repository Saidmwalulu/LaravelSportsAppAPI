<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\FixtureController;
use App\Http\Controllers\CommentsController;

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
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//public routes before Auth
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

Route::get('all-users', [AuthController::class, 'getUsers'])->middleware('jwtAuth','isSuperAdmin'); 
Route::post('update-users', [AuthController::class, 'updateUsers'])->middleware('jwtAuth','isSuperAdmin');

//Authentication
Route::post('me', [AuthController::class, 'me'])->middleware('jwtAuth');
Route::post('user-profile', [AuthController::class, 'profileUser'])->middleware('jwtAuth');
Route::post('user-name', [AuthController::class, 'userName'])->middleware('jwtAuth');
Route::post('change-password', [AuthController::class, 'changePassword'])->middleware('jwtAuth');
Route::post('logout', [AuthController::class, 'logout'])->middleware('jwtAuth');

//user post is one to many
Route::post('posts/create', [PostsController::class, 'createPost'])->middleware('jwtAuth','isAdmin','isSuperAdmin');
Route::post('posts/update', [PostsController::class, 'updatePost'])->middleware('jwtAuth','isAdmin','isSuperAdmin');
Route::post('posts/delete', [PostsController::class, 'deletePost'])->middleware('jwtAuth','isAdmin','isSuperAdmin');
Route::get('posts', [PostsController::class, 'getPosts'])->middleware('jwtAuth');

//user likes is one to many
Route::post('posts/like', [LikesController::class, 'likePost'])->middleware('jwtAuth');

//user comments is one to many
Route::post('comments/create', [CommentsController::class, 'createComment'])->middleware('jwtAuth');
Route::post('comments/update', [CommentsController::class, 'updateComment'])->middleware('jwtAuth');
Route::post('comments/delete', [CommentsController::class, 'deleteComment'])->middleware('jwtAuth');
Route::post('comments', [CommentsController::class, 'getComments'])->middleware('jwtAuth');

//user videos is one to many
Route::post('videos/create', [VideoController::class, 'createVideo'])->middleware('jwtAuth','isAdmin','isSuperAdmin');
Route::get('videos', [VideoController::class, 'getVideos'])->middleware('jwtAuth');
Route::post('videos/edit', [VideoController::class, 'editVideo'])->middleware('jwtAuth','isAdmin','isSuperAdmin');
Route::post('videos/delete', [VideoController::class, 'deleteVideo'])->middleware('jwtAuth','isAdmin','isSuperAdmin');

//user fixtures is one to many
Route::post('fixtures/create', [FixtureController::class, 'createFixture'])->middleware('jwtAuth','isAdmin','isSuperAdmin');
Route::get('fixtures', [FixtureController::class, 'getFixtures'])->middleware('jwtAuth');
Route::post('fixtures/edit', [FixtureController::class, 'editFixture'])->middleware('jwtAuth','isAdmin','isSuperAdmin');
Route::post('fixtures/delete', [FixtureController::class, 'deleteFixture'])->middleware('jwtAuth','isAdmin','isSuperAdmin');



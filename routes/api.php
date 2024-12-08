<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Api\UserController;
use App\Http\Controllers\Admin\Api\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\Api\AchievementController as AdminAchievementController;
use App\Http\Controllers\Admin\Api\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\Api\AboutController as AdminAboutController;
use App\Http\Controllers\Admin\Api\CommitteController as AdminCommitteController;

use App\Http\Controllers\Client\AboutController;
use App\Http\Controllers\Client\CommitteeController;
use App\Http\Controllers\Client\AchievementController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\MemberController;









Route::group(['prefix' => 'admin'], function () {
    Route::group(['prefix' => 'user'], function () {
        Route::post('login', [UserController::class, 'login'])->middleware('guest:sanctum');
        Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
        Route::get('me', [UserController::class, 'me'])->middleware('auth:sanctum');
        Route::post('register', [UserController::class, 'createAccount'])->middleware('guest:sanctum'); 
        Route::post('forgot-password', [UserController::class, 'forgotPassword'])->middleware('guest:sanctum');
    });
    Route::group(['prefix' => 'home', 'controller' => AdminHomeController::class], function () {
        Route::delete('{home}/forceDelete', 'forceDelete')->middleware('auth:sanctum');
        Route::get('trashed', [AdminHomeController::class, 'trashed'])->middleware('auth:sanctum');
        Route::post('{home}/restore', 'restore')->middleware('auth:sanctum');
    });
    Route::apiResource('home', AdminHomeController::class)->middleware('auth:sanctum');

    Route::group(['prefix' => 'achievement', 'controller' => AdminAchievementController::class], function () {
        Route::delete('{achievement}/forceDelete', 'forceDelete')->middleware('auth:sanctum');
        Route::get('trashed', [AdminAchievementController::class, 'trashed'])->middleware('auth:sanctum');
        Route::post('{achievement}/restore', 'restore')->middleware('auth:sanctum');
    });
    Route::apiResource('achievement', AdminAchievementController::class)->middleware('auth:sanctum');
    
    Route::group(['prefix' => 'member', 'controller' => AdminMemberController::class], function () {
        Route::delete('{member}/forceDelete', 'forceDelete')->middleware('auth:sanctum');
        Route::get('trashed', [AdminMemberController::class, 'trashed'])->middleware('auth:sanctum');
        Route::post('{member}/restore', 'restore')->middleware('auth:sanctum');
    });
    Route::apiResource('member', AdminMemberController::class)->middleware('auth:sanctum');

    Route::group(['prefix' => 'about', 'controller' => AdminAboutController::class], function () {
        Route::delete('{about}/forceDelete', 'forceDelete')->middleware('auth:sanctum');
        Route::get('trashed', [AdminAboutController::class, 'trashed'])->middleware('auth:sanctum');
        Route::post('{about}/restore', 'restore')->middleware('auth:sanctum');
    });
    Route::apiResource('about', AdminAboutController::class)->middleware('auth:sanctum');

    
    Route::group(['prefix' => 'committee', 'controller' => AdminCommitteController::class], function () {
        Route::delete('{committee}/forceDelete', 'forceDelete')->middleware('auth:sanctum');
        Route::get('trashed', [AdminCommitteController::class, 'trashed'])->middleware('auth:sanctum');
        Route::post('{committee}/restore', 'restore')->middleware('auth:sanctum');
    });
    Route::apiResource('committee', AdminCommitteController::class)->middleware('auth:sanctum');
});

Route::prefix('client')->group(function () {
    Route::get('about', [AboutController::class, 'index']);
    Route::get('about/{about}', [AboutController::class, 'show']);

    Route::get('committee', [CommitteeController::class, 'index']);
    Route::get('committee/{committee}', [CommitteeController::class, 'show']);

    
    Route::get('achievement', [AchievementController::class, 'index']);
    Route::get('achievement/{achievement}', [AchievementController::class, 'show']);

    Route::get('home', [HomeController::class, 'index']);
    Route::get('home/{home}', [HomeController::class, 'show']);

    Route::get('member', [MemberController::class, 'index']);
    Route::get('member/{member}', [MemberController::class, 'show']);
 });

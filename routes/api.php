<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\ItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function(){
    Route::post('login', 'login')->name('login');
    Route::post('register', 'register')->name('register');
});

Route::middleware('auth:api')->group(function () {
    Route::get('/checklists', [ChecklistController::class, 'index']);
    Route::post('/checklists', [ChecklistController::class, 'store']);
    Route::get('/checklists/{checklist}', [ChecklistController::class, 'show']);
    Route::delete('/checklists/{checklist}', [ChecklistController::class, 'destroy']); // Delete a checklist

    Route::post('/checklists/{checklist}/items', [ItemController::class, 'store']); // Create a new to-do item
    Route::get('/checklists/{checklist}/items/{item}', [ItemController::class, 'show']); // Show item details
    Route::patch('/checklists/{checklist}/items/{item}', [ItemController::class, 'update']); // Update an item
    Route::patch('/checklists/{checklist}/items/{item}/status', [ItemController::class, 'toggleStatus']); // Change item status
    Route::delete('/checklists/{checklist}/items/{item}', [ItemController::class, 'destroy']); // Delete an item
});

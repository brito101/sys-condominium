<?php

use App\Http\Controllers\Api\{
    AuthController,
    BilletController,
    DocumentController,
    LostFoundController,
    ReservationController,
    UnitController,
    WallNoticeController,
    WarningController,
};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return ['pong' => true];
});

Route::get('/401', [AuthController::class, 'unauthorized'])->name('login');

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::post('/auth/validate', [AuthController::class, 'validateToken']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    /** Wall Notices */
    Route::get('/wall-notices', [WallNoticeController::class, 'getAll']);
    Route::post('/wall-notices/{id}/like', [WallNoticeController::class, 'like']);

    /** Documents */
    Route::get('/documents', [DocumentController::class, 'getAll']);

    /** Warnings */
    Route::get('/warnings', [WarningController::class, 'getMyWarnings']);
    Route::post('/warning', [WarningController::class, 'setWarning']);
    Route::post('/warning/file', [WarningController::class, 'addWarningFile']);

    /** Billets */
    Route::get('/billets', [BilletController::class, 'getAll']);

    /** Lost and Found */
    Route::get('/lost-found', [LostFoundController::class, 'getAll']);
    Route::post('/lost-found', [LostFoundController::class, 'insert']);
    Route::put('/lost-found/{id}', [LostFoundController::class, 'update']);

    /** Units */
    Route::get('/unit/{id}', [UnitController::class, 'getInfo']);
    Route::post('/unit/{id}/add-person', [UnitController::class, 'addPerson']);
    Route::post('/unit/{id}/add-vehicle', [UnitController::class, 'addVehicle']);
    Route::post('/unit/{id}/add-pet', [UnitController::class, 'addPet']);
    Route::post('/unit/{id}/remove-person', [UnitController::class, 'removePerson']);
    Route::post('/unit/{id}/remove-vehicle', [UnitController::class, 'removeVehicle']);
    Route::post('/unit/{id}/remove-pet', [UnitController::class, 'removePet']);

    /** Reservations */
    Route::get('/reservations', [ReservationController::class, 'getReservations']);
    Route::post('/reservation/{id}', [ReservationController::class, 'addMyReservations']);
    Route::get('/reservation/{id}/disabled-dates', [ReservationController::class, 'getDisabledDates']);
    Route::get('/reservation/{id}/times', [ReservationController::class, 'getTimes']);
    Route::get('/my-reservations', [ReservationController::class, 'getMyReservations']);
    Route::delete('/reservation/{id}', [ReservationController::class, 'deleteMyReservations']);
});

<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AgencyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\SeniorityController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\PositionLevelController;
use App\Http\Controllers\PositionStatusController;

Route::get('/get-csrf-token', function () {
    return csrf_token();
});

/*Route::get('/agency', [AgencyController::class, 'getAgency']);
Route::post('/agency/insert', [AgencyController::class, 'insertAgency']);
Route::post('/agency/update/{id}', [AgencyController::class, 'updateAgency']);
Route::post('/agency/delete/{id}', [AgencyController::class, 'softDeleteAgency']);

Route::post('/employee/insert', [EmployeeController::class, 'insertEmployee']);
Route::post('/employee/update', [EmployeeController::class, 'updateEmployment']);
Route::post('/employee/update-profile', [EmployeeController::class, 'updateEmployeeProfile']);
Route::post('/employee/delete', [EmployeeController::class, 'softDeleteEmployee']);
Route::get('/employee', [EmployeeController::class, 'getEmployee']);
Route::get('/employee/details/{id}', [EmployeeController::class, 'getEmployeeDetails']);

Route::get('/floor', [FloorController::class, 'getFloor']);
Route::get('/floor/details/{id}', [FloorController::class, 'getFloorDetails']);
Route::post('/floor/insert', [FloorController::class, 'insertFloor']);
Route::post('/floor/update', [FloorController::class, 'updateFloor']);
Route::post('/floor/delete', [FloorController::class, 'softDeleteFloor']);

Route::get('/seniority', [SeniorityController::class, 'getAllSettingSeniority']);
Route::post('/seniority/insert', [SeniorityController::class, 'addSeniority']);
Route::post('/seniority/update', [SeniorityController::class, 'updateSeniority']);
Route::post('/seniority/delete', [SeniorityController::class, 'softDeleteSeniority']);
Route::get('/seniority/{id}', [SeniorityController::class, 'getSeniority']);

Route::get('/position', [PositionController::class, 'getAllPositions']);
Route::post('/position/insert', [PositionController::class, 'addPosition']);
Route::post('/position/update', [PositionController::class, 'updatePosition']);
Route::get('/position/{id}', [PositionController::class, 'getPositionDetail']);
Route::post('/position/delete', [PositionController::class, 'softDeletePosition']);

Route::get('/position-level', [PositionLevelController::class, 'getPositionLevels']);
Route::post('/position-level/insert', [PositionLevelController::class, 'addPositionLevel']);
Route::post('/position-level/update', [PositionLevelController::class, 'updatePositionLevel']);
Route::get('/position-level/{id}', [PositionLevelController::class, 'getPositionLevelDetail']);
Route::post('/position-level/delete', [PositionLevelController::class, 'softDeletePositionLevel']);

Route::get('/position-status', [PositionStatusController::class, 'getPositionStatuses']);
Route::post('/position-status/insert', [PositionStatusController::class, 'addPositionStatus']);
Route::post('/position-status/update', [PositionStatusController::class, 'updatePositionStatus']);
Route::get('/position-status/{id}', [PositionStatusController::class, 'getPositionStatus']);
Route::post('/position-status/delete', [PositionStatusController::class, 'softDeletePositionStatus']);

*/

// Route::get('/', function () {
//     return view('welcome');
// });

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/login", [AuthController::class, "showLogin"])->name("login");
Route::post("/login", [AuthController::class, "authenticate"]);

// Hook Data From Server
Route::post("/api/data", [HookController::class, "dataHandler"]);

Route::middleware(["auth"])->group(function() {
    // Home
    Route::get("/", [HomeController::class, "index"]);

    // Profile
    Route::get("/profile", [HomeController::class, "profile"]);
    Route::post("/profile/reset/password", [AuthController::class, "resetPassword"])->name("resetPassword");

    // Employee
    Route::get("/employees", [EmployeeController::class, "index"])->name("employees");
    Route::post("/employees/add", [EmployeeController::class, "addEmployee"]);
    Route::post("/employees/delete", [EmployeeController::class, "deleteEmp"]);
    Route::post("/employees/all", [EmployeeController::class, "getEmployees"]);
    Route::post("/employees/edit", [EmployeeController::class, "editEmp"]);


    // Item
    Route::get("/items", [ItemController::class, "showItemLists"])->name("items");
    Route::post("/items/all", [ItemController::class, "getItemLists"]);
    Route::post("/items/add", [ItemController::class, "addItem"]);
    Route::post("/items/edit", [ItemController::class, "editItem"]);
    Route::post("/items/delete", [ItemController::class, "deleteItem"]);
    // Item Increase
    Route::get("/items/increase", [ItemController::class, "showIncreaseLists"])->name("increase");
    Route::post("/items/increase/add", [ItemController::class, "addIncreaseItem"]);
    Route::post("/items/increase/edit", [ItemController::class, "editIncreaseItem"]);
    Route::post("/items/increase/delete", [ItemController::class, "deleteIncreaseItem"]);
    Route::post("/items/increase/all", [ItemController::class, "getIncreaseLists"]);
    Route::get("/items/increase/emp-data", [ItemController::class, "getEmployees"]);
    // Item Deduction
    Route::get("/items/deduction", [ItemController::class, "showDeductionLists"])->name("deduction");
    Route::post("/items/deduction/all", [ItemController::class, "getDeductionLists"]);
    Route::get("/items/emp-data", [ItemController::class, "getEmployees"]);
    Route::post("/items/deduction/add", [ItemController::class, "addDeductionItem"]);
    Route::post("/items/deduction/edit", [ItemController::class, "editDeductionItem"]);
    Route::post("/items/deduction/delete", [ItemController::class, "deleteDeductionItem"]);

    // Reports
    Route::get("/reports/export", [ReportController::class, "showExports"]);
    Route::get("reports/export/general", [ReportController::class, "generalReport"])->name("generalReport");
    Route::get("reports/export/increase", [ReportController::class, "increaseReport"])->name("increaseReport");
    Route::get("reports/export/deduction", [ReportController::class, "deductionReport"])->name("deductionReport");
    Route::post("reports/month", [ReportController::class, "getMonth"]);

    Route::get("/logout", [AuthController::class, "logout"]);
});

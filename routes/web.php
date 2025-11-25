<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\Map\MapController;
use App\Http\Controllers\tax\TaxController;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\Account\UserController;
use App\Http\Controllers\home\BookingController;
use App\Http\Controllers\home\FeatureController;
use App\Http\Controllers\home\PricingController;
use App\Http\Controllers\pages\MiscTooManyRequest;
use App\Http\Controllers\payment\PaymentController;
use App\Http\Controllers\reports\ReportsController;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\home\LandingPageController;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\assessor\AssessorController;
use App\Http\Controllers\property\PropertyController;
use App\Http\Controllers\market\MarketValueController;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\assessment\AssessmentController;
use App\Http\Controllers\facilities\FacilitiesController;
use App\Http\Controllers\property\PropertyTypeController;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\request\RequestController;
use App\Http\Controllers\land\LandPropertyController;
use App\Http\Controllers\building\BuildingController;
use App\Http\Controllers\GoogleAuthController;


Route::middleware(['guest', 'throttle:web'])->group(function () {
  Route::get('/register', [RegisterBasic::class, 'index'])->name('auth-register');
  Route::post('/register/add', [RegisterBasic::class, 'store'])->name('auth-register-add');

  Route::get('/', [LoginBasic::class, 'index'])->name('login');
  Route::post('/login/process', [LoginBasic::class, 'login'])->name('login-process')->middleware(['throttle:login']);

  Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect')->middleware(['throttle:login']);
  Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback')->middleware(['throttle:login']);


  Route::get('/register', [RegisterBasic::class, 'index'])->name('auth-register');
  Route::get('/forgot-password', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password');
});

Route::middleware(['auth', 'role:Admin,Employee,User', 'throttle:web'])->group(function () {
  Route::get('/dashboard', [Analytics::class, 'index'])->name('dashboard-analytics');

  Route::get('/properties', [PropertyController::class, 'index'])->name('properties-list');
  Route::get('/properties/lazy', [PropertyController::class, 'lazyLoad']);
  Route::post('/properties/add', [PropertyController::class, 'store'])->name('properties-add');
  Route::post('/properties/update', [PropertyController::class, 'update'])->name('properties-edit');
  Route::post('/properties/delete', [PropertyController::class, 'delete'])->name('properties-delete');

  Route::get('/myproperty', [LandPropertyController::class, 'index'])->name('myproperty');
  Route::post('/myproperty/request', [LandPropertyController::class, 'request'])->name('myproperty-request');
  Route::get('/myproperty/pdf/{id}', [LandPropertyController::class, 'viewPdf'])->name('myproperty');


  Route::get('/gis-map', [MapController::class, 'index'])->name('map-gis');
  Route::get('/gis-map/parcels', [MapController::class, 'getParcels'])->name('map-gis-parcel');
  Route::get('/gis-map', [MapController::class, 'index'])->name('map-gis');
  Route::get('/gis-map/search', [MapController::class, 'searchLot'])->name('map-gis-parcel-search');

  Route::middleware(['role:Admin,Employee'])->group(function () {


    Route::get('/logs', [Analytics::class, 'logslist'])->name('logs-list');

    Route::get('/building-rate', [BuildingController::class, 'index'])->name('bulding-rate');
    Route::post('/building-rate/add', [BuildingController::class, 'store'])->name('bulding-rate-add');
    Route::post('/building-rate/update', [BuildingController::class, 'update'])->name('bulding-rate-update');
    Route::post('/building-rate/delete', [BuildingController::class, 'delete'])->name('bulding-rate-delete');

    Route::get('/request', [RequestController::class, 'index'])->name('request-list');
    Route::post('/request/decline', [RequestController::class, 'decline'])->name('request-list-decline');
    Route::post('/request/accept', [RequestController::class, 'accept'])->name('request-list-accept');

    Route::get('/user', [UserController::class, 'index'])->name('user-accounts');
    Route::post('/user/add', [UserController::class, 'store'])->name('user-add');
    Route::post('/user/update', [UserController::class, 'update'])->name('user-update');
    Route::post('/user/delete', [UserController::class, 'delete'])->name('user-delete');

    Route::get('/assessor', [AssessorController::class, 'index'])->name('assessor-accounts');
    Route::post('/assessor/add', [AssessorController::class, 'store'])->name('assessor-add');
    Route::post('/assessor/update', [AssessorController::class, 'update'])->name('assessor-update');
    Route::post('/assessor/delete', [AssessorController::class, 'delete'])->name('assessor-delete');

    Route::get('/property-type', [PropertyTypeController::class, 'index'])->name('property-types');
    Route::post('/property-type/add', [PropertyTypeController::class, 'store'])->name('property-type-add');
    Route::post('/property-type/update', [PropertyTypeController::class, 'update'])->name('property-type-update');

    Route::get('/schedule-market-values', [MarketValueController::class, 'index'])->name('schedule-market-values');
    Route::post('/schedule-market-values/add', [MarketValueController::class, 'store'])->name('schedule-market-values-add');
    Route::post('/schedule-market-values/update', [MarketValueController::class, 'update'])->name('schedule-market-values-update');

    Route::get('/assessment', [AssessmentController::class, 'index'])->name('assessment-list');
    Route::get('/assessment/lazy', [AssessmentController::class, 'lazyLoad']);
    Route::post('/assessment/add', [AssessmentController::class, 'store'])->name('assessment-add');
    Route::post('/assessment/update', [AssessmentController::class, 'update'])->name('assessment-edit');
    Route::post('/assessment/delete', [AssessmentController::class, 'delete'])->name('assessment-delete');

    Route::get('/building-assessment', [AssessmentController::class, 'buidingAssessment'])->name('assessment-list-building');
    Route::get('/building-assessment/lazy', [AssessmentController::class, 'lazyLoadBuilding']);
    Route::post('/building-assessment/add', [AssessmentController::class, 'buildingAssessmentStore'])->name('assessment-list-building-add');
    Route::post('/building-assessment/update', [AssessmentController::class, 'buildingAssessmentUpdate'])->name('assessment-list-building-update');

    Route::get('/tax/pdf',[TaxController::class, 'viewPdf'])->name('tax-pdf');
    Route::get('/assessments/pdf/{id}', [AssessmentController::class, 'viewPdf'])->name('reports-pdf');
    Route::get('/building-assessment/pdf/{id}', [AssessmentController::class, 'buildingPDF'])->name('building-pdf');
  });

  Route::get('/pages/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-account');
});

Route::get('/logout', [LoginBasic::class, 'logoutAccount'])->name('logout-process')->middleware(['throttle:web']);
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
Route::get('/pages/misc-under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');
Route::get('/pages/misc-too-many-request', [MiscTooManyRequest::class, 'index'])->name('pages-misc-too-many-request');

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\Crew\CrewController;
use App\Http\Controllers\Backend\Meal\MealController;
use App\Http\Controllers\Backend\Admin\AdminController;
use App\Http\Controllers\Backend\Hotel\HotelController;
use App\Http\Controllers\Backend\AgenFee\AgenFeeController;
use App\Http\Controllers\Backend\Vehicle\VehicleController;
use App\Http\Controllers\Backend\Facility\FacilityController;
use App\Http\Controllers\Backend\ReserveFee\ReserveFeeController;
use App\Http\Controllers\Backend\ServiceFee\ServiceFeeController;
use App\Http\Controllers\Backend\Destinastion\DestinationController;
use App\Http\Controllers\Backend\PackageTour\GeneratePackageController;

Route::get('/', function () {
    return view('frontend/index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/admin/login', [AdminController::class, 'AdminLogin'])->name('admin.login');

Route::middleware(['auth', 'roles:admin'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');
    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    Route::post('/admin/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');

    Route::get('/admin/change/password', [AdminController::class, 'AdminChangePassword'])->name('admin.change.password');
    Route::post('/admin/password/update', [AdminController::class, 'AdminPasswordUpdate'])->name('admin.password.update');
});


// Admin Group Middlware
Route::middleware(['auth', 'roles:admin'])->group(function () {

    // Destinations all Route
    Route::controller(DestinationController::class)->group(function () {

        Route::get('/all/destinations', 'AllDestinations')->name('all.destinations');
        Route::get('/add/destination', 'AddDestination')->name('add.destination');
        Route::post('/destination/store', 'StoreDestination')->name('destination.store');
        Route::get('/edit/destination/{id}', 'EditDestination')->name('edit.destination');
        Route::post('/destination/update', 'UpdateDestination')->name('destination.update');
        Route::delete('/delete/destination/{id}', 'DeleteDestination')->name('delete.destination');
        Route::get('/import/destinations', 'PageImportDestinations')->name('import.destinations');
        Route::post('/destination/import', 'ImportDestination')->name('destinations.import');
    });

    // Vehicles all Route
    Route::controller(VehicleController::class)->group(function () {

        Route::get('/all/vehicles', 'AllVehicles')->name('all.vehicles');
        Route::get('/add/vehicle', 'AddVehicle')->name('add.vehicle');
        Route::post('/vehicle/store', 'StoreVehicle')->name('vehicle.store');
        Route::get('/edit/vehicle/{id}', 'EditVehicle')->name('edit.vehicle');
        Route::post('/vehicle/update', 'UpdateVehicle')->name('vehicle.update');
        Route::delete('/delete/vehicle/{id}', 'DeleteVehicle')->name('delete.vehicle');
        // Route::get('/import/vehicles', 'PageImportVehicles')->name('import.vehicles');
        // Route::post('/vehicle/import', 'ImportVehicles')->name('vehicles.import');
    });

    // Service all Route
    Route::controller(ServiceFeeController::class)->group(function () {

        Route::get('/all/service', 'AllService')->name('all.service');
        Route::post('/add/service', 'StoreServiceFee')->name('servicefee.store');
        Route::put('/update/service-fee/{id}', 'UpdateServiceFee')->name('update.service.fee');
        Route::delete('/delete/service-fee/{id}', 'DeleteServiceFee')->name('delete.service.fee');
    });

    // Crew All Route
    Route::controller(CrewController::class)->group(function () {

        Route::get('/all/crew', 'AllCrew')->name('all.crew');
        Route::post('/add/crew', 'StoreCrew')->name('crew.store');
        Route::put('/update/crew/{id}', 'UpdateCrew')->name('update.crew');
        Route::delete('/delete/crew/{id}', 'DeleteCrew')->name('delete.crew');
    });

    // Crew All Route
    Route::controller(AgenFeeController::class)->group(function () {

        Route::get('/all/agen-fee', 'AllAgenFee')->name('all.agen.fee');
        Route::post('/update/agen-fee', 'UpdateAgenFee')->name('update.agen.fee');
    });

    // Facility all Route
    Route::controller(FacilityController::class)->group(function () {

        Route::get('/all/facility', 'AllFacility')->name('all.facility');
        Route::post('/add/facility', 'StoreFacility')->name('facility.store');
        Route::put('/update/facility/{id}', 'UpdateFacility')->name('update.facility');
        Route::delete('/delete/facility/{id}', 'DeleteFacility')->name('delete.facility');

    });

    // Meal all Route
    Route::controller(MealController::class)->group(function () {

        Route::get('/all/meal', 'AllMeal')->name('all.meals');
        Route::post('/add/meal', 'StoreMeal')->name('meal.store');
        Route::put('/update/meal/{id}', 'UpdateMeal')->name('update.meal');
        Route::delete('/delete/meal/{id}', 'DeleteMeal')->name('delete.meal');

    });

    // ReserveFee all Route
    Route::controller(ReserveFeeController::class)->group(function () {

        Route::get('/all/reservefee', 'AllReserveFee')->name('all.reservefee');
        Route::post('/add/reservefee', 'StoreReserveFee')->name('reservefee.store');
        Route::put('/update/reservefee/{id}', 'UpdateReserveFee')->name('update.reservefee');
        Route::delete('/delete/reservefee/{id}', 'DeleteReserveFee')->name('delete.reservefee');

    });

    // Packege all Route
    Route::controller(GeneratePackageController::class)->group(function () {

        Route::get('/all/packages', 'AllPackage')->name('all.packages');
        Route::get('/generate/package', 'GeneratePackage')->name('generate.package');
        Route::post('/package/generate', 'generateCodeOneday')->name('generatecode.package');
        Route::get('/edit/package/{id}', 'EditGeneratePackage')->name('edit.package');
        Route::put('/update/package/{id}', 'UpdateGenerateCodeOneday')->name('update.package');
        Route::delete('/delete/package/{id}', 'DeletePackage')->name('delete.package');
        Route::get('/packages/agen/{id}', 'AllPackagesAgen')->name('all.packages.agen');
        Route::get('/show/package/{id}', 'PackageShow')->name('show.package');

    });

    // Hotels all Route
    Route::controller(HotelController::class)->group(function () {

        Route::get('/all/hotels', 'AllHotels')->name('all.hotels');
        Route::get('/add/hotel', 'AddHotel')->name('add.hotel');
        Route::post('/hotel/store', 'StoreHotel')->name('hotel.store');
        Route::get('/edit/hotel/{id}', 'EditHotel')->name('edit.hotel');
        Route::put('/hotel/update', 'UpdateHotel')->name('hotel.update');
        Route::delete('/delete/hotel/{id}', 'DeleteHotel')->name('delete.hotel');
    });
});

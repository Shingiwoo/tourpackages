<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\Agen\AgenController;
use App\Http\Controllers\Backend\Crew\CrewController;
use App\Http\Controllers\Backend\Meal\MealController;
use App\Http\Controllers\Backend\Role\RoleController;
use App\Http\Controllers\Backend\Admin\AdminController;
use App\Http\Controllers\Backend\Hotel\HotelController;
use App\Http\Controllers\Agen\Core\AgenServiceController;
use App\Http\Controllers\Backend\AgenFee\AgenFeeController;
use App\Http\Controllers\Backend\Vehicle\VehicleController;
use App\Http\Controllers\Agen\Core\BookingServiceController;
use App\Http\Controllers\Agen\Core\PackageServiceController;
use App\Http\Controllers\Backend\Facility\FacilityController;
use App\Http\Controllers\Backend\Custom\CustomPackageController;
use App\Http\Controllers\Backend\Permission\PermissionController;
use App\Http\Controllers\Backend\ReserveFee\ReserveFeeController;
use App\Http\Controllers\Backend\ServiceFee\ServiceFeeController;
use App\Http\Controllers\Backend\Destinastion\DestinationController;
use App\Http\Controllers\Backend\PackageTour\GeneratePackageController;
use App\Http\Controllers\Backend\PackageTour\GenerateAllPackageController;
use App\Http\Controllers\Backend\PackageTour\GenerateTwodayPackageController;
use App\Http\Controllers\Backend\PackageTour\GenerateFourdayPackageController;
use App\Http\Controllers\Backend\PackageTour\GenerateThreedayPackageController;

Route::get('/', function () {
    return view('frontend/index');
});

Route::get('/admin/dashboard', [AdminController::class, 'index'])
    ->middleware(['auth', 'roles:admin'])
    ->name('admin.dashboard');

Route::get('/dashboard', [AgenServiceController::class, 'AgenDashboard'])
    ->middleware(['auth', 'roles:agen'])
    ->name('agen.dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';

Route::get('/admin/login', [AdminController::class, 'AdminLogin'])->name('admin.login');

Route::middleware(['auth', 'roles:admin'])->group(function () {

    // Profile Route

    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');
    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    Route::post('/admin/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');

    Route::get('/admin/change/password', [AdminController::class, 'AdminChangePassword'])->name('admin.change.password');
    Route::post('/admin/password/update', [AdminController::class, 'AdminPasswordUpdate'])->name('admin.password.update');

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
        Route::get('/export/destination', 'Export')->name('export.destinations');
    });

    // Vehicles all Route
    Route::controller(VehicleController::class)->group(function () {

        Route::get('/all/vehicles', 'AllVehicles')->name('all.vehicles');
        Route::get('/add/vehicle', 'AddVehicle')->name('add.vehicle');
        Route::post('/vehicle/store', 'StoreVehicle')->name('vehicle.store');
        Route::get('/edit/vehicle/{id}', 'EditVehicle')->name('edit.vehicle');
        Route::post('/vehicle/update', 'UpdateVehicle')->name('vehicle.update');
        Route::delete('/delete/vehicle/{id}', 'DeleteVehicle')->name('delete.vehicle');
        Route::get('/import/vehicles', 'PageImportVehicles')->name('import.vehicles');
        Route::get('/export/vehicles', 'Export')->name('export.vehicles');
        Route::post('/import/vehicle', 'Import')->name('import.vehicle');
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
    Route::controller(AgenController::class)->group(function () {

        Route::get('/all/agen-fee', 'AllAgenFee')->name('all.agen.fee');
        Route::post('/update/agen-fee', 'UpdateAgenFee')->name('update.agen.fee');

        Route::get('/all/agen', 'AllAgen')->name('all.agen');
        Route::get('/add/agen', 'AddAgen')->name('add.agen');
        Route::post('/store/agen', 'StoreAgen')->name('store.agen');
        Route::get('/edit/agen/{id}', 'EditAgen')->name('edit.agen');
        Route::patch('/update/agen/{id}', 'UpdateAgen')->name('update.agen');
        Route::post('/delete/agen/{id}', 'DeleteAgen')->name('delete.agen');
    });

    // Facility all Route
    Route::controller(FacilityController::class)->group(function () {

        Route::get('/all/facility', 'AllFacility')->name('all.facility');
        Route::post('/add/facility', 'StoreFacility')->name('facility.store');
        Route::get('/edit/facility/{id}', 'EditFacility')->name('edit.facility');
        Route::post('/facility/update', 'UpdateFacility')->name('facility.update');
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

    // Hotels all Route
    Route::controller(HotelController::class)->group(function () {

        Route::get('/all/hotels', 'AllHotels')->name('all.hotels');
        Route::get('/add/hotel', 'AddHotel')->name('add.hotel');
        Route::post('/hotel/store', 'StoreHotel')->name('hotel.store');
        Route::get('/edit/hotel/{id}', 'EditHotel')->name('edit.hotel');
        Route::put('/hotel/update/{id}', 'UpdateHotel')->name('hotel.update');
        Route::delete('/delete/hotel/{id}', 'DeleteHotel')->name('delete.hotel');
    });

    // Unified package routes
    Route::controller(GenerateAllPackageController::class)->group(function () {
        Route::get('/all/packages', 'GeneratePackages')->name('generate.all.packages');
        Route::post('/packages/generate', 'generate')->name('generate.packages');
    });

    // Package Oneday all Route
    Route::controller(GeneratePackageController::class)->group(function () {

        // Oneday
        Route::get('/all/oneday/packages', 'AllPackage')->name('all.packages');
        Route::get('/generate/oneday/package', 'GeneratePackage')->name('generate.package');
        Route::post('/package/oneday/generate', 'generateCodeOneday')->name('generatecode.package');
        Route::get('/edit/oneday/package/{id}', 'EditGeneratePackage')->name('edit.package');
        Route::put('/update/oneday/package/{id}', 'UpdateGenerateCodeOneday')->name('update.package');
        Route::delete('/delete/oneday/package/{id}', 'DeletePackage')->name('delete.package');
        Route::get('/packages/agen/{id}', 'AllPackagesAgen')->name('all.packages.agen');
        Route::get('/show/oneday/package/{id}', 'PackageShow')->name('show.package');
    });

    // Package Twoday all Route
    Route::controller(GenerateTwodayPackageController::class)->group(function () {

        // Oneday
        Route::get('/all/twoday/packages', 'AllTwoDayPackage')->name('all.twoday.packages');
        Route::get('/generate/twoday/package', 'GenerateTwodayPackage')->name('generate.twoday.package');
        Route::post('/package/twoday/generate', 'generateCodeTwoday')->name('generatecode.twoday.package');
        Route::get('/edit/twoday/package/{id}', 'EditGenerateTwodayPackage')->name('edit.twoday.package');
        Route::put('/update/twoday/package/{id}', 'UpdateGenerateCodeTwoday')->name('update.twoday.package');
        Route::delete('/delete/twoday/package/{id}', 'DeleteTwodayPackage')->name('delete.twoday.package');
        Route::get('/packages/twoday/agen/{id}', 'AllTwodayPackagesAgen')->name('all.twoday.packages.agen');
        Route::get('/show/twoday/package/{id}', 'PackageTwodayShow')->name('show.twoday.package');
    });

    // Package Threeday all Route
    Route::controller(GenerateThreedayPackageController::class)->group(function () {

        // Oneday
        Route::get('/all/threeday/packages', 'AllThreeDayPackage')->name('all.threeday.packages');
        Route::get('/generate/threeday/package', 'GenerateThreeDayPackage')->name('generate.threeday.package');
        Route::post('/package/threeday/generate', 'generateCodeThreeDay')->name('generatecode.threeday.package');
        Route::get('/edit/threeday/package/{id}', 'EditGenerateThreeDayPackage')->name('edit.threeday.package');
        Route::put('/update/threeday/package/{id}', 'UpdateGenerateCodeThreeDay')->name('update.threeday.package');
        Route::delete('/delete/threeday/package/{id}', 'DeleteThreeDayPackage')->name('delete.threeday.package');
        Route::get('/packages/threeday/agen/{id}', 'AllThreeDayPackagesAgen')->name('all.threeday.packages.agen');
        Route::get('/show/threeday/package/{id}', 'PackageThreeDayShow')->name('show.threeday.package');
    });

    // Package Fourday all Route
    Route::controller(GenerateFourdayPackageController::class)->group(function () {

        // Oneday
        Route::get('/all/fourday/packages', 'AllFourDayPackage')->name('all.fourday.packages');
        Route::get('/generate/fourday/package', 'GenerateFourDayPackage')->name('generate.fourday.package');
        Route::post('/package/fourday/generate', 'generateCodeFourDay')->name('generatecode.fourday.package');
        Route::get('/edit/fourday/package/{id}', 'EditGenerateFourDayPackage')->name('edit.fourday.package');
        Route::put('/update/fourday/package/{id}', 'UpdateGenerateCodeFourDay')->name('update.fourday.package');
        Route::delete('/delete/fourday/package/{id}', 'DeleteFourDayPackage')->name('delete.fourday.package');
        Route::get('/packages/fourday/agen/{id}', 'AllFourDayPackagesAgen')->name('all.fourday.packages.agen');
        Route::get('/show/fourday/package/{id}', 'PackageFourDayShow')->name('show.fourday.package');
    });

    // Permissions all Route
    Route::controller(PermissionController::class)->group(function () {
        Route::get('/all/permissions', 'AllPermissions')->name('all.permissions');
        Route::post('/store/permission', 'StorePermission')->name('permission.store');
        Route::get('/edit/permission/{id}', 'EditPermission')->name('edit.permission');
        Route::patch('/update/permission/{id}', 'UpdatePermission')->name('update.permission');
        Route::delete('/delete/permission/{id}', 'DeletePermission')->name('delete.permission');
        Route::get('/import/permissions', 'PageImportPermission')->name('import.permissions');
        Route::get('/export/permissions', 'Export')->name('export.permissions');
        Route::post('/import/permission', 'Import')->name('import.permission');
    });

    // Roles all Route
    Route::controller(RoleController::class)->group(function () {
        Route::get('/all/roles', 'AllRoles')->name('all.roles');
        Route::post('/store/role', 'StoreRole')->name('role.store');
        Route::get('/edit/role/{id}', 'EditRole')->name('edit.role');
        Route::patch('/update/role/{id}', 'UpdateRole')->name('update.role');
        Route::delete('/delete/role/{id}', 'DeleteRole')->name('delete.role');


        Route::get('/add/roles/permission', 'AddRolesPermission')->name('add.roles.permission');
        Route::post('/role/permission/store', 'RolePermissionStore')->name('role.permission.store');
        Route::get('/all/role/permission', 'AllRolePermission')->name('all.role.permission');
        Route::get('/admin/edit/role/{id}', 'AdminEditRole')->name('admin.edit.role');
        Route::patch('/admin/update/role/{id}', 'AdminUpdateRole')->name('admin.update.role');
        Route::delete('/admin/delete/role/{id}', 'AdminDeleteRole')->name('admin.delete.role');

    });

    // Admin Manage all Route
    Route::controller(AdminController::class)->group(function () {

        Route::get('/all/admin', 'AllAdmin')->name('all.admin');
        Route::get('/add/admin', 'AddAdmin')->name('add.admin');
        Route::post('/store/admin', 'StoreAdmin')->name('store.admin');
        Route::get('/edit/admin/{id}', 'EditAdmin')->name('edit.admin');
        Route::patch('/update/admin/{id}', 'UpdateAdmin')->name('update.admin');
        Route::delete('/delete/admin/{id}', 'DeleteAdmin')->name('delete.admin');


    });

    Route::controller(CustomPackageController::class)->group(function () {
        Route::get('/all/custom-package', 'CustomDashboard')->name('all.custom.package');
        Route::post('/store/custom-package', 'StoreData')->name('store.custom.package');
    });
});

Route::get('/login', [AgenServiceController::class, 'AgenLogin'])->name('login');

route::middleware(['auth','roles:agen'])->group(function(){

    Route::controller(AgenServiceController::class)->group(function () {

        // Profile Route
        Route::get('/agen/dashboard', 'AgenDashboard')->name('dashboard');
        Route::get('/agen/logout', 'AgenLogout')->name('agen.logout');
        Route::get('/agen/profile', 'AgenProfile')->name('agen.profile');
        Route::post('/agen/profile/store', 'AgenProfileStore')->name('agen.profile.store');

        // Change password Route
        Route::get('/agen/change/password', 'AgenChangePassword')->name('agen.change.password');
        Route::post('/agen/password/update', 'AgenPasswordUpdate')->name('agen.password.update');

    });

    //package list agen
    Route::controller(PackageServiceController::class)->group(function () {

        Route::get('/agen/all-package', 'AllPackage')->name('agen.all.package');
        Route::get('/show/package/{id}', 'PackageShow')->name('package.show');

    });

    //package list agen
    Route::controller(BookingServiceController::class)->group(function () {

        Route::get('/agen/all-booking', 'AllBooking')->name('agen.booking');

    });


});

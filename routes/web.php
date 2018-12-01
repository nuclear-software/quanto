<?php

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
Route::get('register', function(){
	return redirect('/login');
});
Route::post('register', function(){
	return redirect('/login');
});

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function () {
    //    Route::get('/link1', function ()    {
//        // Uses Auth Middleware
//    });

    //Please do not remove this if you want adminlte:route and adminlte:link commands to works correctly.
    #adminlte_routes
});


Route::middleware(['auth'])->prefix('admin')->group(function(){
    
	Route::get('sections/dt','Admin\SectionController@toDatatable')->name('sections.dt');
    Route::get('sections/create-only','Admin\SectionController@createOnly')->name('sections.create_only');
    Route::post('sections/store-only','Admin\SectionController@storeOnly')->name('sections.store_only');
    Route::resource('sections', 'Admin\SectionController');

    Route::get('users/dt','Admin\UserController@toDatatable')->name('users.dt');
    Route::resource('users', 'Admin\UserController');
    
    Route::get('permissions/dt','Admin\PermissionController@toDatatable')->name('permissions.dt');
    Route::resource('permissions', 'Admin\PermissionController');
    
    Route::get('roles/dt','Admin\RoleController@toDatatable')->name('roles.dt');
    Route::resource('roles', 'Admin\RoleController');
    
    Route::get('companies/dt','Admin\CompanyController@toDatatable')->name('companies.dt');
    Route::resource('companies', 'Admin\CompanyController');

    Route::get('memberships/dt','Admin\MembershipController@toDatatable')->name('memberships.dt');
    Route::resource('memberships', 'Admin\MembershipController');

    Route::get('establishments/dt','Admin\EstablishmentController@toDatatable')->name('establishments.dt');
    Route::resource('establishments', 'Admin\EstablishmentController');

    Route::get('establishment_tables/dt','Admin\EstablishmentTableController@toDatatable')->name('establishment_tables.dt');
    Route::resource('establishment_tables', 'Admin\EstablishmentTableController');

    Route::get('products/dt','Admin\ProductController@toDatatable')->name('products.dt');
    Route::resource('products', 'Admin\ProductController');

    Route::get('accounts/dt','Admin\AccountController@toDatatable')->name('accounts.dt');
    Route::resource('accounts', 'Admin\AccountController');

    Route::get('account_components/dt','Admin\AccountComponentController@toDatatable')->name('account_components.dt');
    Route::resource('account_components', 'Admin\AccountComponentController');

    Route::get('product_references/dt','Admin\ProductReferenceController@toDatatable')->name('product_references.dt');
    Route::resource('product_references', 'Admin\ProductReferenceController');

});
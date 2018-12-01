<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1','middleware' => 'auth:api'], function () {
    //    Route::resource('task', 'TasksController');

    //Please do not remove this if you want adminlte:route and adminlte:link commands to works correctly.
    #adminlte_api_routes

    Route::apiResource('companies', 'Api\CompanyController');

    Route::apiResource('memberships', 'Api\MembershipController');

    Route::apiResource('establishments', 'Api\EstablishmentController');

    Route::apiResource('establishment_tables', 'Api\EstablishmentTableController');

    Route::apiResource('products', 'Api\ProductController');

    Route::apiResource('accounts', 'Api\AccountController');

    Route::apiResource('account_components', 'Api\AccountComponentController');

    Route::apiResource('product_references', 'Api\ProductReferenceController');

});



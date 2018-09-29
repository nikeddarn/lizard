<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/**
 * ****************************************************************
 * ToDo this routes must be removed after setup completed !!!!!!!!!!!
 * ****************************************************************
 */
Route::get('/setup', 'Setup\SetupController@setup');


// --------------------------------------------- Common Routes ---------------------------------------------------------
// ---------------------------------------------------------------------------------------------------------------------

Route::get('/language/{locale}', 'LocaleController@switchLocale')->name('language');

Route::get('/', 'MainPageController@show');


// --------------------------------------------- Auth Routes ---------------------------------------------------------
// ---------------------------------------------------------------------------------------------------------------------

Auth::routes();

Route::get('/admin/login', 'Auth\LoginController@showAdminLoginForm')->name('admin.login');


// --------------------------------------------- User Routes -----------------------------------------------------------
// ---------------------------------------------------------------------------------------------------------------------

Route::middleware(['auth:web'])->group(function () {

    // balance
    Route::get('/user/balance', 'User\BalanceController@show')->name('user.balance.show');

    // notifications
    Route::get('/user/notifications', 'User\NotificationController@show')->name('user.notifications.current');
    Route::get('/user/notifications/mark/{id}', 'User\NotificationController@markAsRead')->name('user.notifications.mark');
    Route::get('/user/notifications/all', 'User\NotificationController@show')->name('user.notifications.all');

    // shipments
    Route::get('/user/shipments', 'User\ShipmentController@show')->name('user.shipments.current');
    Route::get('/user/shipments/all', 'User\ShipmentController@show')->name('user.shipments.all');

    // orders
    Route::get('/user/orders', 'User\OrderController@show')->name('user.orders.current');
    Route::get('/user/orders/all', 'User\OrderController@show')->name('user.orders.all');

    // reclamations
    Route::get('/user/reclamations', 'User\ReclamationController@show')->name('user.reclamations.current');
    Route::get('/user/reclamations/all', 'User\ReclamationController@show')->name('user.reclamations.all');

    // payments
    Route::get('/user/payments', 'User\PaymentController@show')->name('user.payments.current');
    Route::get('/user/payments/all', 'User\PaymentController@show')->name('user.payments.all');

    // profile
    Route::get('/user/profile', 'User\ProfileController@show')->name('user.profile.show');
    Route::get('/user/profile/edit', 'User\ProfileController@showProfileForm')->name('user.profile.edit');
    Route::post('/user/profile/save', 'User\ProfileController@save')->name('user.profile.save');

    // password
    Route::get('/user/password', 'User\PasswordController@showChangePasswordForm')->name('user.password.show');
    Route::post('/user/password/change', 'User\PasswordController@changePassword')->name('user.password.change');
});

// --------------------------------------------- Admin Routes -----------------------------------------------------------
// ---------------------------------------------------------------------------------------------------------------------

Route::middleware('auth.admin')->group(function (){

    // overview page
    Route::get('/admin', 'Admin\OverviewController@index')->name('admin');

    // categories
    Route::get('/admin/categories', 'Admin\CategoryController@index')->name('admin.categories.index');
    Route::get('/admin/categories/create', 'Admin\CategoryController@create')->name('admin.categories.create');
    Route::post('/admin/categories/', 'Admin\CategoryController@store')->name('admin.categories.store');
    Route::get('/admin/categories/show/{id}', 'Admin\CategoryController@show')->name('admin.categories.show');
    Route::get('/admin/categories/{id}/edit', 'Admin\CategoryController@edit')->name('admin.categories.edit');
    Route::put('/admin/categories/{id}', 'Admin\CategoryController@update')->name('admin.categories.update');
    Route::delete('/admin/categories/{id}', 'Admin\CategoryController@destroy')->name('admin.categories.destroy');
    // upload "summernote" editor images
    Route::post('/admin/categories/upload/image', 'Admin\CategoryController@uploadImage')->name('admin.categories.upload.image');

    // products
    Route::get('/admin/products', 'Admin\ProductController@index')->name('admin.products.index');
    Route::get('/admin/products/create', 'Admin\ProductController@create')->name('admin.products.create');
    Route::post('/admin/products/', 'Admin\ProductController@store')->name('admin.products.store');
    Route::get('/admin/products/show/{id}', 'Admin\ProductController@show')->name('admin.products.show');
    Route::get('/admin/products/{id}/edit', 'Admin\ProductController@edit')->name('admin.products.edit');
    Route::put('/admin/products/{id}', 'Admin\ProductController@update')->name('admin.products.update');
    Route::delete('/admin/products/{id}', 'Admin\ProductController@destroy')->name('admin.products.destroy');
    // upload "summernote" editor images
    Route::post('/admin/products/upload/image', 'Admin\ProductController@uploadImage')->name('admin.products.upload.image');

    // attributes
    Route::get('/admin/attributes', 'Admin\AttributeController@index')->name('admin.attributes.index');
    Route::get('/admin/attributes/create', 'Admin\AttributeController@create')->name('admin.attributes.create');
    Route::post('/admin/attributes/', 'Admin\AttributeController@store')->name('admin.attributes.store');
    Route::get('/admin/attributes/show/{id}', 'Admin\AttributeController@show')->name('admin.attributes.show');
    Route::get('/admin/attributes/{id}/edit', 'Admin\AttributeController@edit')->name('admin.attributes.edit');
    Route::put('/admin/attributes/{id}', 'Admin\AttributeController@update')->name('admin.attributes.update');
    Route::delete('/admin/attributes/{id}', 'Admin\AttributeController@destroy')->name('admin.attributes.destroy');
    // upload "summernote" editor images
    Route::post('/admin/attributes/upload/image', 'Admin\AttributeController@uploadImage')->name('admin.attributes.upload.image');


});
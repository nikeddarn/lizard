<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


// --------------------------------------------- Common Routes ---------------------------------------------------------
// ---------------------------------------------------------------------------------------------------------------------

Auth::routes();

Route::get('/language/{locale}', 'LocaleController@switchLocale')->name('language');

Route::get('/', 'MainPageController@show');


// --------------------------------------------- User Routes ---------------------------------------------------------
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

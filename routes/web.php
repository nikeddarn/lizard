<?php

use Illuminate\Support\Facades\Route;

/**
 * ****************************************************************
 * ToDo this routes must be removed after setup completed !!!!!!!!!!!
 * ****************************************************************
 */
Route::get('/setup', 'Setup\SetupController@setup');
Route::get('/setup/vendors', 'Setup\SetupController@setupVendors');


// --------------------------------------------- Common Routes ---------------------------------------------------------
// ---------------------------------------------------------------------------------------------------------------------

Route::get('/{locale?}', 'MainPageController@show')->name('main');


// --------------------------------------------- Auth Routes ---------------------------------------------------------
// ---------------------------------------------------------------------------------------------------------------------

Route::get('/user/login/{locale?}', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/user/register/{locale?}', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

Route::get('password/reset/{locale?}', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}/{locale?}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

Route::get('/admin/login', 'Auth\LoginController@showAdminLoginForm')->name('admin.login');


// --------------------------------------------- User Routes -----------------------------------------------------------
// ---------------------------------------------------------------------------------------------------------------------

Route::middleware(['auth:web'])->group(function () {

    // balance
    Route::get('/user/balance/{locale?}', 'User\BalanceController@show')->name('user.balance.show');

    // notifications
    Route::get('/user/notifications/{locale?}', 'User\NotificationController@show')->name('user.notifications.current');
    Route::get('/user/notifications/mark/{id}', 'User\NotificationController@markAsRead')->name('user.notifications.mark');
    Route::get('/user/notifications/all/{locale?}', 'User\NotificationController@show')->name('user.notifications.all');

    // shipments
    Route::get('/user/shipments/{locale?}', 'User\ShipmentController@show')->name('user.shipments.current');
    Route::get('/user/shipments/all/{locale?}', 'User\ShipmentController@show')->name('user.shipments.all');

    // orders
    Route::get('/user/orders/{locale?}', 'User\OrderController@show')->name('user.orders.current');
    Route::get('/user/orders/all/{locale?}', 'User\OrderController@show')->name('user.orders.all');

    // reclamations
    Route::get('/user/reclamations/{locale?}', 'User\ReclamationController@show')->name('user.reclamations.current');
    Route::get('/user/reclamations/all/{locale?}', 'User\ReclamationController@show')->name('user.reclamations.all');

    // payments
    Route::get('/user/payments/{locale?}', 'User\PaymentController@show')->name('user.payments.current');
    Route::get('/user/payments/all/{locale?}', 'User\PaymentController@show')->name('user.payments.all');

    // profile
    Route::get('/user/profile/{locale?}', 'User\ProfileController@index')->name('user.profile.show');
    Route::get('/user/profile/edit/{locale?}', 'User\ProfileController@edit')->name('user.profile.edit');
    Route::post('/user/profile/update', 'User\ProfileController@update')->name('user.profile.save');

    // password
    Route::get('/user/password/{locale?}', 'User\PasswordController@showChangePasswordForm')->name('user.password.show');
    Route::post('/user/password/change', 'User\PasswordController@changePassword')->name('user.password.change');

});

// favourite products
Route::get('/user/favourites/{locale?}', 'User\FavouriteProductController@index')->name('user.favourites.index');
Route::get('/user/favourites/add/{id}', 'User\FavouriteProductController@addProductToFavourite')->name('user.favourites.add');
Route::get('/user/favourites/remove/{id}', 'User\FavouriteProductController@removeProductFromFavourite')->name('user.favourites.remove');

// recent products
Route::get('/user/recent/{locale?}', 'User\RecentProductController@index')->name('user.recent.index');




// --------------------------------------------- Shop Routes -----------------------------------------------------------
// ---------------------------------------------------------------------------------------------------------------------

// subcategories
Route::get('/categories/{url}/{locale?}', 'Shop\CategoryController@index')->name('shop.category.index');

// multi filter category
Route::get('/category/{url}/filtered/{locale?}', 'Shop\MultiFilterCategoryController@index')->name('shop.category.filter.multi');

// leaf category
Route::get('/category/{url}/{locale?}', 'Shop\LeafCategoryController@index')->name('shop.category.leaf.index');

// single filter category
Route::get('/subcategory/{url}/{filter}/{locale?}', 'Shop\FilterCategoryController@index')->name('shop.category.filter.single');


// product details and comments
Route::get('/product/{url}/{locale?}', 'Shop\ProductDetailsController@index')->name('shop.product.index');
Route::post('/product/comments', 'Shop\ProductCommentController@store')->name('product.comments.store');

// user cart
Route::get('/shop/cart/{locale?}', 'Shop\CartController@index')->name('shop.cart.index');
Route::get('/shop/cart/add/{id}', 'Shop\CartController@addProduct')->name('shop.cart.add');
Route::post('/shop/cart/count/{id}', 'Shop\CartController@addProduct')->name('shop.cart.count');


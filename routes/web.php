<?php

use Illuminate\Support\Facades\Route;

/**
 * ****************************************************************
 * ToDo this routes must be removed after setup completed !!!!!!!!!!!
 * ****************************************************************
 */
//Route::get('/setup', 'Setup\SetupController@setup');
//Route::get('/setup/users', 'Setup\SetupController@setupUsers');
//Route::get('/setup/vendors', 'Setup\SetupController@setupVendors');
//Route::get('/setup/pages', 'Setup\SetupController@setupStaticPages');

// --------------------------------------------- Common Routes -------------------------------------------------

Route::get('/{locale?}', 'Pages\MainPageController@index')->name('main')
    ->where('locale', '(' . implode('|', config('app.available_locales')) . ')');

// --------------------------------------------- Auth Routes ------------------------------------------------------

Route::get('/login/{locale?}', 'Auth\LoginController@showLoginForm')->name('login');
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
    Route::get('/user/profile/edit/{locale?}', 'User\ProfileController@edit')->name('user.profile.edit');
    Route::get('/user/profile/{locale?}', 'User\ProfileController@index')->name('user.profile.show');

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
Route::get('/category/{url}/{locale?}', 'Shop\CategoryController@index')->name('shop.category.index');

// multi filter category
Route::get('/products/{url}/filtered/{locale?}', 'Shop\MultiFilterCategoryController@index')->name('shop.category.filter.multi');

// single filter category
Route::get('/products/{url}/filter/{filter}/{locale?}', 'Shop\FilterCategoryController@index')->name('shop.category.filter.single');

// leaf category
Route::get('/products/{url}/{locale?}', 'Shop\LeafCategoryController@index')->name('shop.category.leaf.index');

// product details and comments
Route::get('/product/{url}/{locale?}', 'Shop\ProductDetailsController@index')->name('shop.product.index');
Route::post('/product/comments', 'Shop\ProductCommentController@store')->name('product.comments.store');

// user cart
Route::get('/shop/cart/{locale?}', 'Shop\CartController@index')->name('shop.cart.index');
Route::get('/shop/cart/add/{id}', 'Shop\CartController@addProduct')->name('shop.cart.add');
Route::get('/shop/cart/remove/{id}', 'Shop\CartController@removeProduct')->name('shop.cart.remove');
Route::post('/shop/cart/count', 'Shop\CartController@addProductCount')->name('shop.cart.count');
Route::get('/shop/cart/increment/{id}', 'Shop\CartController@increaseProductCount')->name('shop.cart.increment');
Route::get('/shop/cart/decrement/{id}', 'Shop\CartController@decreaseProductCount')->name('shop.cart.decrement');

// checkout
Route::get('/shop/checkout/create/{locale?}', 'Shop\OrderController@create')->name('shop.order.create');
Route::post('/shop/checkout/store', 'Shop\OrderController@store')->name('shop.order.store');

//  Search Routes
Route::post('/search/{locale?}', 'Shop\SearchController@index')->name('shop.search.index');
Route::get('/search/{locale?}', 'Shop\SearchController@results')->name('shop.search.results');


// -------------------------------- Static pages -----------------------------------

Route::get('/shop/delivery/{locale?}', 'Pages\DeliveryPageController@index')->name('shop.delivery.index');

Route::get('/shop/payments/{locale?}', 'Pages\PaymentsPageController@index')->name('shop.payments.index');

Route::get('/shop/return/{locale?}', 'Pages\ReturnPageController@index')->name('shop.return.index');

Route::get('/shop/warranty/{locale?}', 'Pages\WarrantyPageController@index')->name('shop.warranty.index');

Route::get('/shop/about/{locale?}', 'Pages\AboutPageController@index')->name('shop.about.index');

Route::get('/shop/contacts/{locale?}', 'Pages\ContactsPageController@index')->name('shop.contacts.index');

<?php

use App\Http\Middleware\Locale\UserLocale;
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


Route::get('/sitemap.xml', 'SitemapController@index');

Route::get('/hotline_ru.xml', 'Export\Hotline\HotlineExportController@index');

// --------------------------------------------- Common Routes -------------------------------------------------
Route::where(['locale' => '(' . implode('|', config('app.available_locales')) . ')'])
    ->group(function () {

        Route::get('/{locale?}', 'Pages\MainPageController@index')->name('main');

// --------------------------------------------- Auth Routes ------------------------------------------------------

        Route::get('/login/{locale?}', 'Auth\LoginController@showLoginForm')->name('login');
        Route::post('login', 'Auth\LoginController@login');
        Route::post('logout', 'Auth\LoginController@logout')->name('logout');

        Route::get('/user/register/{locale?}', 'Auth\RegisterController@showRegistrationForm')->name('register');
        Route::post('register', 'Auth\RegisterController@register');

        Route::get('password/request/{locale?}', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('password/email/{locale?}', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email')->middleware(UserLocale::class);
        Route::get('password/reset/{token}/{locale?}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

        Route::get('/admin/login', 'Auth\LoginController@showAdminLoginForm')->name('admin.login');


// --------------------------------------------- User Routes --------------------------------------------------
// ---------------------------------------------------------------------------------------------------

        Route::middleware(['auth:web'])->group(function () {

            // profile
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


// --------------------------------------------- Shop Routes -----------------------------------------------
// ------------------------------------------------------------------------------------------------

// subcategories
        Route::get('/category/{url}/{locale?}', 'Shop\CategoryController@index')->name('shop.category.index');

// multi filter category
        Route::get('/products/{url}/filtered/{locale?}', 'Shop\MultiFilterCategoryController@index')->name('shop.category.filter.multi');

// single filter category
        Route::get('/products/{url}/{attribute}/{filter}/{locale?}', 'Shop\FilterCategoryController@index')->name('shop.category.filter.single');

// leaf category
        Route::get('/products/{url}/{locale?}', 'Shop\LeafCategoryController@index')->name('shop.category.leaf.index');

// product details and comments
        Route::get('/product/{url}/{locale?}', 'Shop\ProductDetailsController@index')->name('shop.product.index');
        Route::post('/product/comments', 'Shop\ProductCommentController@store')->name('product.comments.store');

// user cart
        Route::get('/shop/cart/{locale?}', 'Shop\CartController@index')->name('shop.cart.index');
        Route::get('/shop/cart/add/{id}/{locale?}', 'Shop\CartController@addProduct')->name('shop.cart.add');
        Route::get('/shop/cart/remove/{id}', 'Shop\CartController@removeProduct')->name('shop.cart.remove');
        Route::post('/shop/cart/count', 'Shop\CartController@addProductCount')->name('shop.cart.count');
        Route::get('/shop/cart/increment/{id}', 'Shop\CartController@increaseProductCount')->name('shop.cart.increment');
        Route::get('/shop/cart/decrement/{id}', 'Shop\CartController@decreaseProductCount')->name('shop.cart.decrement');

// Checkout
        Route::get('/shop/checkout/create/{locale?}', 'Shop\CheckoutController@create')->name('shop.checkout.create');
        Route::post('/shop/checkout/store', 'Shop\CheckoutController@store')->name('shop.checkout.store');


//  Search Routes
        Route::post('/search/{locale?}', 'Shop\SearchController@index')->name('shop.search.index');
        Route::get('/search/{locale?}', 'Shop\SearchController@results')->name('shop.search.results');

// orders
        Route::get('/user/orders/{locale?}', 'User\OrderController@index')->name('user.orders.index');

        Route::post('/user/order/update', 'User\OrderController@update')->name('user.order.update');
        Route::post('/user/order/update/delivery', 'User\OrderController@updateDelivery')->name('user.order.update.delivery');

        Route::post('/user/order/cancel', 'User\OrderController@cancel')->name('user.order.cancel');


// -------------------------------- Static pages -----------------------------------

        Route::get('/shop/delivery/{locale?}', 'Pages\DeliveryPageController@index')->name('shop.delivery.index');

        Route::get('/shop/payments/{locale?}', 'Pages\PaymentsPageController@index')->name('shop.payments.index');

        Route::get('/shop/return/{locale?}', 'Pages\ReturnPageController@index')->name('shop.return.index');

        Route::get('/shop/warranty/{locale?}', 'Pages\WarrantyPageController@index')->name('shop.warranty.index');

        Route::get('/shop/about/{locale?}', 'Pages\AboutPageController@index')->name('shop.about.index');

        Route::get('/shop/contacts/{locale?}', 'Pages\ContactsPageController@index')->name('shop.contacts.index');

    });

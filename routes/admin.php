<?php

use Illuminate\Support\Facades\Route;

// --------------------------------------------- Admin Routes ----------------------------------------------------------
// ---------------------------------------------------------------------------------------------------------------------

// overview page
Route::get('/admin/overview', 'Admin\OverviewController@index')->name('admin.overview');

// categories
Route::get('/admin/categories', 'Admin\CategoryController@index')->name('admin.categories.index');
Route::get('/admin/categories/create', 'Admin\CategoryController@create')->name('admin.categories.create');
Route::post('/admin/categories', 'Admin\CategoryController@store')->name('admin.categories.store');
Route::get('/admin/categories/show/{id}', 'Admin\CategoryController@show')->name('admin.categories.show');
Route::get('/admin/categories/{id}/edit', 'Admin\CategoryController@edit')->name('admin.categories.edit');
Route::put('/admin/categories/{id}', 'Admin\CategoryController@update')->name('admin.categories.update');
Route::delete('/admin/categories/{id}', 'Admin\CategoryController@destroy')->name('admin.categories.destroy');
Route::get('/admin/categories/{id}/up', 'Admin\CategoryController@up')->name('admin.categories.up');
Route::get('/admin/categories/{id}/down', 'Admin\CategoryController@down')->name('admin.categories.down');
// upload "summernote" editor images
Route::post('/admin/categories/upload/image', 'Admin\CategoryController@uploadImage')->name('admin.categories.upload.image');

// category filters
//    Route::get('/admin/categories/{id}/filter/create', 'Admin\CategoryFilterController@create')->name('admin.categories.filter.create');
//    Route::post('/admin/categories/filter', 'Admin\CategoryFilterController@store')->name('admin.categories.filter.store');
//    Route::delete('/admin/categories/filter/destroy', 'Admin\CategoryFilterController@destroy')->name('admin.categories.filter.destroy');

// virtual categories (SEO)
Route::get('/admin/categories/virtual', 'Admin\VirtualCategoryController@index')->name('admin.categories.virtual.index');
Route::get('/admin/categories/virtual/create', 'Admin\VirtualCategoryController@create')->name('admin.categories.virtual.create');
Route::post('/admin/categories/virtual', 'Admin\VirtualCategoryController@store')->name('admin.categories.virtual.store');
Route::get('/admin/categories/virtual/{id}/show', 'Admin\VirtualCategoryController@show')->name('admin.categories.virtual.show');
Route::get('/admin/categories/virtual/{id}/edit', 'Admin\VirtualCategoryController@edit')->name('admin.categories.virtual.edit');
Route::put('/admin/categories/virtual/{id}', 'Admin\VirtualCategoryController@update')->name('admin.categories.virtual.update');
Route::delete('/admin/categories/virtual/{id}', 'Admin\VirtualCategoryController@destroy')->name('admin.categories.virtual.destroy');

// products
Route::get('/admin/products', 'Admin\ProductController@index')->name('admin.products.index');
Route::get('/admin/products/create', 'Admin\ProductController@create')->name('admin.products.create');
Route::post('/admin/products', 'Admin\ProductController@store')->name('admin.products.store');
Route::get('/admin/products/show/{id}', 'Admin\ProductController@show')->name('admin.products.show');
Route::get('/admin/products/{id}/edit', 'Admin\ProductController@edit')->name('admin.products.edit');
Route::put('/admin/products/{id}', 'Admin\ProductController@update')->name('admin.products.update');
Route::delete('/admin/products/{id}', 'Admin\ProductController@destroy')->name('admin.products.destroy');
// upload "summernote" editor images
Route::post('/admin/products/upload/image', 'Admin\ProductController@uploadImage')->name('admin.products.upload.image');

// product images
Route::get('/admin/products/{id}/image/create', 'Admin\ProductImageController@create')->name('admin.products.image.create');
Route::post('/admin/products/image', 'Admin\ProductImageController@store')->name('admin.products.image.store');
Route::delete('/admin/products/image/{id}', 'Admin\ProductImageController@destroy')->name('admin.products.image.destroy');
// set image as priority
Route::post('/admin/products/image/{id}/priority', 'Admin\ProductImageController@priority')->name('admin.products.image.priority');

// product attributes
Route::get('/admin/products/{id}/attribute/create', 'Admin\ProductAttributeController@create')->name('admin.products.attribute.create');
Route::post('/admin/products/attribute', 'Admin\ProductAttributeController@store')->name('admin.products.attribute.store');
Route::delete('/admin/products/attribute/destroy', 'Admin\ProductAttributeController@destroy')->name('admin.products.attribute.destroy');

// product filters
Route::get('/admin/products/{id}/filter/create', 'Admin\ProductFilterController@create')->name('admin.products.filter.create');
Route::post('/admin/products/filter', 'Admin\ProductFilterController@store')->name('admin.products.filter.store');
Route::delete('/admin/products/filter/destroy', 'Admin\ProductFilterController@destroy')->name('admin.products.filter.destroy');

// product categories
Route::get('/admin/products/{id}/category/create', 'Admin\ProductCategoryController@create')->name('admin.products.category.create');
Route::post('/admin/products/category', 'Admin\ProductCategoryController@store')->name('admin.products.category.store');
Route::delete('/admin/products/category/destroy', 'Admin\ProductCategoryController@destroy')->name('admin.products.category.destroy');

// brands
//    Route::get('/admin/brands', 'Admin\BrandController@index')->name('admin.brands.index');
//    Route::get('/admin/brands/create', 'Admin\BrandController@create')->name('admin.brands.create');
//    Route::post('/admin/brands', 'Admin\BrandController@store')->name('admin.brands.store');
//    Route::get('/admin/brands/{id}/edit', 'Admin\BrandController@edit')->name('admin.brands.edit');
//    Route::put('/admin/brands/{id}', 'Admin\BrandController@update')->name('admin.brands.update');
//    Route::delete('/admin/brands/{id}', 'Admin\BrandController@destroy')->name('admin.brands.destroy');

// attributes
Route::get('/admin/attributes', 'Admin\AttributeController@index')->name('admin.attributes.index');
Route::get('/admin/attributes/create', 'Admin\AttributeController@create')->name('admin.attributes.create');
Route::post('/admin/attributes', 'Admin\AttributeController@store')->name('admin.attributes.store');
Route::get('/admin/attributes/show/{id}', 'Admin\AttributeController@show')->name('admin.attributes.show');
Route::get('/admin/attributes/{id}/edit', 'Admin\AttributeController@edit')->name('admin.attributes.edit');
Route::put('/admin/attributes/{id}', 'Admin\AttributeController@update')->name('admin.attributes.update');
Route::delete('/admin/attributes/{id}', 'Admin\AttributeController@destroy')->name('admin.attributes.destroy');

// attribute values
Route::get('/admin/attributes/{id}/value/create', 'Admin\AttributeValueController@create')->name('admin.attributes.value.create');
Route::post('/admin/attributes/value', 'Admin\AttributeValueController@store')->name('admin.attributes.value.store');
Route::get('/admin/attributes/value/{id}/edit', 'Admin\AttributeValueController@edit')->name('admin.attributes.value.edit');
Route::put('/admin/attributes/value/{id}', 'Admin\AttributeValueController@update')->name('admin.attribute.values.update');
Route::delete('/admin/attributes/values/{id}', 'Admin\AttributeValueController@destroy')->name('admin.attribute.values.destroy');

// filters
Route::get('/admin/filters', 'Admin\FilterController@index')->name('admin.filters.index');
Route::get('/admin/filters/create', 'Admin\FilterController@create')->name('admin.filters.create');
Route::post('/admin/filters', 'Admin\FilterController@store')->name('admin.filters.store');
Route::get('/admin/filters/show/{id}', 'Admin\FilterController@show')->name('admin.filters.show');
Route::get('/admin/filters/{id}/edit', 'Admin\FilterController@edit')->name('admin.filters.edit');
Route::put('/admin/filters/{id}', 'Admin\FilterController@update')->name('admin.filters.update');
Route::delete('/admin/filters/{id}', 'Admin\FilterController@destroy')->name('admin.filters.destroy');

// users
Route::get('/admin/users/customers', 'Admin\UserController@customers')->name('admin.users.customers');
Route::get('/admin/users/administrators', 'Admin\UserController@administrators')->name('admin.users.administrators');
Route::get('/admin/users/show/{id}', 'Admin\UserController@show')->name('admin.users.show');
Route::get('/admin/users/{id}/edit', 'Admin\UserController@edit')->name('admin.users.edit');
Route::put('/admin/users/{id}', 'Admin\UserController@update')->name('admin.users.update');
Route::delete('/admin/users/{id}', 'Admin\UserController@destroy')->name('admin.users.destroy');

// user roles
Route::get('/admin/users/{id}/role/create', 'Admin\UserRoleController@create')->name('admin.users.role.create');
Route::post('/admin/users/role', 'Admin\UserRoleController@store')->name('admin.users.role.store');
Route::delete('/admin/users/role/destroy', 'Admin\UserRoleController@destroy')->name('admin.users.role.destroy');


// ---------------------------------------------- Vendor Routes --------------------------------------------------------
// ---------------------------------------------------------------------------------------------------------------------

// categories
Route::get('/admin/vendor/{vendorId}/categories', 'Vendor\VendorCategoryController@index')->name('vendor.categories.index');

Route::get('/admin/categories/synchronized', 'Vendor\VendorCategoryController@synchronized')->name('vendor.categories.synchronized');

Route::get('/admin/vendor/{vendorId}/category/{vendorOwnCategoryId}/sync', 'Vendor\VendorCategoryController@sync')->name('vendor.category.sync');
Route::post('/admin/vendor/category/link', 'Vendor\VendorCategoryController@link')->name('vendor.category.link');
Route::delete('/admin/vendor/category/unlink', 'Vendor\VendorCategoryController@unlink')->name('vendor.category.unlink');

Route::post('/admin/vendor/category/products/auto/on', 'Vendor\VendorCategoryController@autoDownloadOn')->name('vendor.category.products.auto.on');
Route::post('/admin/vendor/category/products/auto/off', 'Vendor\VendorCategoryController@autoDownloadOff')->name('vendor.category.products.auto.off');


//products
Route::get('/admin/vendor/{vendorId}/category/{vendorCategoryId}/local/{localCategoryId}/products', 'Vendor\VendorProductController@index')->name('vendor.category.products.index');

Route::post('/admin/vendor/category/products/upload', 'Vendor\VendorProductController@upload')->name('vendor.category.products.upload');
Route::post('/admin/vendor/category/products/upload/all', 'Vendor\VendorProductController@uploadAll')->name('vendor.category.products.upload.all');
Route::post('/admin/vendor/category/products/uploaded', 'Vendor\VendorProductController@uploaded')->name('vendor.category.products.uploaded');


// ---------------------------------------------- Synchronization Routes -----------------------------------------------
// ---------------------------------------------------------------------------------------------------------------------


Route::get('/admin/synchronization', 'Vendor\VendorSynchronizationController@index')->name('vendor.synchronization.index');
Route::get('/admin/synchronization/sync/{vendorId}', 'Vendor\VendorSynchronizationController@synchronize')->name('vendor.synchronization.synchronize');


// ---------------------------------------------- Settings Routes ------------------------------------------------------
// ---------------------------------------------------------------------------------------------------------------------

// seo
Route::get('/admin/settings/seo/edit', 'Admin\SeoSettingsController@edit')->name('admin.settings.seo.edit');
Route::post('/admin/settings/seo/update', 'Admin\SeoSettingsController@update')->name('admin.settings.seo.update');



<?php

use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', 'SitemapController@index');

// --------------------------------------------- Admin Routes --------------------------------------

// overview page
Route::get('/admin', 'Admin\OverviewController@index')->name('admin.overview');

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
// is category empty  ?
Route::get('/admin/categories/{id}/empty', 'Admin\CategoryController@isEmpty')->name('admin.categories.empty');

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

// product publishing
Route::post('/admin/products/publish/on', 'Admin\ProductController@publishProduct')->name('admin.products.publish.on');
Route::post('/admin/products/publish/off', 'Admin\ProductController@unPublishProduct')->name('admin.products.publish.off');

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
//Route::get('/admin/products/{id}/filter/create', 'Admin\ProductFilterController@create')->name('admin.products.filter.create');
//Route::post('/admin/products/filter', 'Admin\ProductFilterController@store')->name('admin.products.filter.store');
//Route::delete('/admin/products/filter/destroy', 'Admin\ProductFilterController@destroy')->name('admin.products.filter.destroy');

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

// show and hide in product filters
Route::get('/admin/attributes/{id}/filter/show', 'Admin\AttributeController@makeAttributeFilterVisible')->name('admin.attributes.filter.show');
Route::get('/admin/attributes/{id}/filter/hide', 'Admin\AttributeController@makeAttributeFilterHidden')->name('admin.attributes.filter.hide');

// allow and disallow index categories with values of this filter
Route::get('/admin/attributes/{id}/index/allow', 'Admin\AttributeController@allowRobotsIndex')->name('admin.attributes.index.allow');
Route::get('/admin/attributes/{id}/index/disallow', 'Admin\AttributeController@disallowRobotsIndex')->name('admin.attributes.index.disallow');

// attribute values
Route::get('/admin/attributes/{id}/value/create', 'Admin\AttributeValueController@create')->name('admin.attributes.value.create');
Route::post('/admin/attributes/value', 'Admin\AttributeValueController@store')->name('admin.attributes.value.store');
Route::get('/admin/attributes/value/{id}/edit', 'Admin\AttributeValueController@edit')->name('admin.attributes.value.edit');
Route::put('/admin/attributes/value/{id}', 'Admin\AttributeValueController@update')->name('admin.attribute.values.update');
Route::delete('/admin/attributes/values/{id}', 'Admin\AttributeValueController@destroy')->name('admin.attribute.values.destroy');

// filters
//Route::get('/admin/filters', 'Admin\FilterController@index')->name('admin.filters.index');
//Route::get('/admin/filters/create', 'Admin\FilterController@create')->name('admin.filters.create');
//Route::post('/admin/filters', 'Admin\FilterController@store')->name('admin.filters.store');
//Route::get('/admin/filters/show/{id}', 'Admin\FilterController@show')->name('admin.filters.show');
//Route::get('/admin/filters/{id}/edit', 'Admin\FilterController@edit')->name('admin.filters.edit');
//Route::put('/admin/filters/{id}', 'Admin\FilterController@update')->name('admin.filters.update');
//Route::delete('/admin/filters/{id}', 'Admin\FilterController@destroy')->name('admin.filters.destroy');

// users
Route::get('/admin/users/customers', 'Admin\UserController@index')->name('admin.users.customers');
Route::get('/admin/users/customers/show/{id}', 'Admin\UserController@show')->name('admin.users.customers.show');
Route::delete('/admin/users/customers/{id}', 'Admin\UserController@destroy')->name('admin.users.customers.destroy');

// increase and decrease price groups
Route::post('/admin/users/group/up', 'Admin\UserController@increasePriceGroup')->name('admin.users.group.up');
Route::post('/admin/users/group/down', 'Admin\UserController@decreasePriceGroup')->name('admin.users.group.down');


// admins
Route::get('/admin/users/administrators', 'Admin\AdminController@index')->name('admin.users.administrators');
Route::get('/admin/users/administrators/show/{id}', 'Admin\AdminController@show')->name('admin.users.administrators.show');
Route::delete('/admin/users/administrators/{id}', 'Admin\AdminController@destroy')->name('admin.users.administrators.destroy');

// admin roles
Route::get('/admin/users/{id}/role/create', 'Admin\UserRoleController@create')->name('admin.users.role.create');
Route::post('/admin/users/role', 'Admin\UserRoleController@store')->name('admin.users.role.store');
Route::delete('/admin/users/role/destroy', 'Admin\UserRoleController@destroy')->name('admin.users.role.destroy');


// ---------------------------------------------- Vendor Routes--------------------------------------------
// --------------------------------------------------------------------------------------------------------

// ---------------------- vendor catalog -----------------------------

// categories tree
Route::get('/admin/vendor/{vendorId}/catalog/categories', 'Vendor\VendorCatalogController@categoriesTree')->name('vendor.catalog.categories.tree');

// products of category
Route::get('/admin/vendor/{vendorId}/category/{vendorCategoryId}/products', 'Vendor\VendorCatalogController@categoryProducts')->name('vendor.catalog.category.products');


// ---------------------- vendor categories -----------------------------

// vendor categories list
Route::get('/admin/vendor/{vendorId}/category/list', 'Vendor\VendorCategoryController@index')->name('vendor.category.list');

// show vendor category
Route::get('/admin/vendor/category/{vendorCategoriesId}', 'Vendor\VendorCategoryController@show')->name('vendor.category.show');

// vendor category create
Route::get('/admin/vendor/{vendorId}/category/{vendorOwnCategoryId}/create', 'Vendor\VendorCategoryController@create')->name('vendor.category.create');

// vendor category store
Route::post('/admin/vendor/category/store', 'Vendor\VendorCategoryController@store')->name('vendor.category.store');

// vendor category edit
Route::get('/admin/vendor/category/{vendorCategoriesId}/edit', 'Vendor\VendorCategoryController@edit')->name('vendor.category.edit');

// vendor category store
Route::post('/admin/vendor/category/update', 'Vendor\VendorCategoryController@update')->name('vendor.category.update');

// vendor category delete
Route::delete('/admin/vendor/category/delete', 'Vendor\VendorCategoryController@delete')->name('vendor.category.delete');

// --------------------------------- local vendor categories -------------------------------------

// vendor local category create
Route::get('/admin/vendor/category/{vendorCategoryId}/local/create', 'Vendor\VendorLocalCategoryController@create')->name('vendor.category.local.create');

// vendor local category store
Route::post('/admin/vendor/category/local/store', 'Vendor\VendorLocalCategoryController@store')->name('vendor.category.local.store');

// vendor local category delete
Route::delete('/admin/vendor/category/local/delete', 'Vendor\VendorLocalCategoryController@delete')->name('vendor.category.local.delete');

// turn on auto download new products
Route::post('/admin/vendor/category/products/auto/on', 'Vendor\VendorLocalCategoryController@autoDownloadOn')->name('vendor.category.products.auto.on');

// turn off auto download new products
Route::post('/admin/vendor/category/products/auto/off', 'Vendor\VendorLocalCategoryController@autoDownloadOff')->name('vendor.category.products.auto.off');

//--------------- synchronizing vendor products --------------------

// synchronizing products
Route::get('/admin/vendor/category/{vendorCategoryId}/local/{localCategoryId}/products/sync', 'Vendor\VendorSynchronizingProductsController@sync')->name('vendor.category.products.sync');

// download selected
Route::post('/admin/vendor/category/products/download/selected', 'Vendor\VendorSynchronizingProductsController@downloadSelected')->name('vendor.category.products.download.selected');

// download all products of vendor category
Route::post('/admin/vendor/category/products/download/all', 'Vendor\VendorSynchronizingProductsController@downloadAll')->name('vendor.category.products.download.all');

// downloaded vendor products ids
Route::post('/admin/vendor/category/products/downloaded/ids', 'Vendor\VendorSynchronizingProductsController@downloadedIds')->name('vendor.category.products.downloaded.ids');


// ------------------ vendor category synchronized products ---------------------------------

// downloaded products
Route::get('/admin/vendor/category/{vendorCategoryId}/local/{localCategoryId}/products/downloaded', 'Vendor\VendorProductController@downloaded')->name('vendor.category.products.downloaded');

// delete vendor category product
Route::delete('/admin/vendor/category/products/destroy', 'Vendor\VendorProductController@delete')->name('vendor.category.products.destroy');


// ---------------------------------------------- Synchronization Routes -----------------------------------------------
// ---------------------------------------------------------------------------------------------------------------------

// synchronized categories list
Route::get('/admin/categories/synchronized', 'Vendor\VendorSynchronizationController@synchronizedCategories')->name('vendor.categories.synchronized');

// synchronization jobs queue
Route::get('/admin/synchronization/queue', 'Vendor\VendorSynchronizationController@synchronizationQueue')->name('vendor.synchronization.index');

// do synchronize vendor
Route::get('/admin/synchronization/sync/{vendorId}', 'Vendor\VendorSynchronizationController@synchronize')->name('vendor.synchronization.synchronize');


// ---------------------------------------------- Settings Routes ------------------------------------------------------
// ---------------------------------------------------------------------------------------------------------------------

// seo
Route::get('/admin/settings/seo/edit', 'Settings\SeoSettingsController@edit')->name('admin.settings.seo.edit');
Route::post('/admin/settings/seo/update', 'Settings\SeoSettingsController@update')->name('admin.settings.seo.update');

// shop
Route::get('/admin/settings/shop/edit', 'Settings\ShopSettingsController@edit')->name('admin.settings.shop.edit');
Route::post('/admin/settings/shop/update', 'Settings\ShopSettingsController@update')->name('admin.settings.shop.update');

// shop
Route::get('/admin/settings/shop/edit', 'Settings\ShopSettingsController@edit')->name('admin.settings.shop.edit');
Route::post('/admin/settings/shop/update', 'Settings\ShopSettingsController@update')->name('admin.settings.shop.update');

// vendor
Route::get('/admin/settings/vendor/edit', 'Settings\VendorSettingsController@edit')->name('admin.settings.vendor.edit');
Route::post('/admin/settings/vendor/update', 'Settings\VendorSettingsController@update')->name('admin.settings.vendor.update');

// -------------------------------- Page Content -------------------------------------------------

// ---------------------------- common
Route::get('/admin/content/common/edit', 'Content\CommonContentController@edit')->name('admin.content.common.edit');
Route::post('/admin/content/common/update', 'Content\CommonContentController@update')->name('admin.content.common.update');

// ------------------------------- main

Route::get('/admin/content/main/edit', 'Content\MainContentController@edit')->name('admin.content.main.edit');

//seo
Route::post('/admin/content/main/update/seo', 'Content\MainContentController@updateSeo')->name('admin.content.main.update.seo');

//slider
Route::post('/admin/content/main/sort/slides', 'Content\MainContentController@sortSlides')->name('admin.content.main.sort.slides');

// products groups
Route::post('/admin/content/main/sort/groups', 'Content\MainContentController@sortGroups')->name('admin.content.main.sort.groups');


// -------------------------------- Slides -----------------------------------
Route::get('/admin/slider/{slider_id}/slide/create', 'Content\SlideController@create')->name('admin.slider.slide.create');
Route::post('/admin/slider/slide/store', 'Content\SlideController@store')->name('admin.slider.slide.store');

Route::get('/admin/slider/slide/{slide_id}/edit', 'Content\SlideController@edit')->name('admin.slider.slide.edit');
Route::post('/admin/slider/slide/update', 'Content\SlideController@update')->name('admin.slider.slide.update');

Route::post('/admin/content/main/delete/slide', 'Content\MainContentController@delete')->name('admin.slider.slide.delete');

// -------------------------------- Product Groups -----------------------------------
Route::get('/admin/product/group/create', 'Content\ProductGroupController@create')->name('admin.product.group.create');
Route::post('/admin/product/group/store', 'Content\ProductGroupController@store')->name('admin.product.group.store');

Route::get('/admin/product/group/{group_id}/edit', 'Content\ProductGroupController@edit')->name('admin.product.group.edit');
Route::post('/admin/product/group/update', 'Content\ProductGroupController@update')->name('admin.product.group.update');

Route::post('/admin/product/group/delete', 'Content\ProductGroupController@delete')->name('admin.product.group.delete');


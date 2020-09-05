<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

//Site
Route::get("site/home", 'SiteController@home');


//Orders
Route::get("orders/active", "OrdersController@active");
Route::get("orders/month", "OrdersController@monthly");
Route::get("orders/month/{id}", "OrdersController@monthly");
Route::get("orders/state/{id}", "OrdersController@state");
Route::get("orders/history/{year}/{month}", "OrdersController@history");
Route::get("orders/history/{year}/{month}/{state}", "OrdersController@history");
Route::get("orders/load/history", "OrdersController@loadHistory");
Route::get("orders/details/{id}", "OrdersController@details");
Route::get("orders/edit/details", "OrdersController@editOrderInfo");
Route::get("orders/set/new/{id}", "OrdersController@setNew");
Route::get("orders/set/ready/{id}", "OrdersController@setReady");
Route::get("orders/set/cancelled/{id}", "OrdersController@setCancelled");
Route::get("orders/set/indelivery/{id}", "OrdersController@setInDelivery");
Route::get("orders/set/delivered/{id}", "OrdersController@setDelivered");
Route::get("orders/create/return/{id}", "OrdersController@setPartiallyReturned");
Route::get("orders/return/{id}", "OrdersController@setFullyReturned");
Route::get("orders/toggle/item/{id}", "OrdersController@toggleItem");
Route::get("orders/delete/item/{id}", "OrdersController@deleteItem");
Route::post("orders/edit/details", "OrdersController@editOrderInfo");
Route::post("orders/collect/payment", "OrdersController@collectNormalPayment");
Route::get("orders/settle/payment/{id}", "OrdersController@settleFromClientBalance");
Route::post("orders/collect/delivery", "OrdersController@collectDeliveryPayment");
Route::post("orders/set/discount", "OrdersController@setDiscount");
Route::post("orders/assign/driver", "OrdersController@assignDriver");
Route::post("orders/add/items/{id}", "OrdersController@insertNewItems");
Route::get("orders/add", "OrdersController@addNew");
Route::post("orders/insert", "OrdersController@insert");
Route::post("orders/change/quantity", "OrdersController@changeQuantity");


//Inventory
Route::get("inventory/entry/new", "InventoryController@entry");
Route::post("inventory/insert/entry", "InventoryController@insert");
Route::get("inventory/current/stock", "InventoryController@stock");
Route::get("inventory/transactions", "InventoryController@transactions");
Route::get("inventory/transaction/{code}", "InventoryController@transactionDetails");

//Products 
Route::get('products/show/all', 'ProductsController@home');
Route::get('products/sale', 'ProductsController@sale');
Route::get('products/new', 'ProductsController@new');
Route::get('products/filter/category', 'ProductsController@filterCategory');
Route::post('products/category', 'ProductsController@showCategory');
Route::post('products/subcategory', 'ProductsController@showSubCategory');
Route::get('products/show/catg/sub/{id}', 'ProductsController@home');
Route::get('products/details/{id}', 'ProductsController@details');
Route::get('products/add', 'ProductsController@add');
Route::post('products/insert', 'ProductsController@insert');
Route::get('products/edit/{id}', 'ProductsController@edit');
Route::post('products/update', 'ProductsController@update');
Route::post('products/ingredients/add/{id}', 'ProductsController@addIngredients');
Route::get('products/ingredients/delete/{id}', 'ProductsController@deleteIngredient');


//Clients
Route::get('clients/show/all','ClientsController@home');
Route::get('clients/show/latest','ClientsController@latest');
Route::get('clients/show/top', 'ClientsController@top');
Route::get('clients/edit/{id}', 'ClientsController@edit');
Route::get('clients/add', 'ClientsController@add');
Route::get('clients/profile/{id}', 'ClientsController@profile');
Route::post('clients/pay', 'ClientsController@pay');
Route::post('clients/insert', 'ClientsController@insert');
Route::post('clients/update', 'ClientsController@update');

//Payment Options
Route::get('paymentoptions/show', 'PaymentOptionsController@home');
Route::get('paymentoptions/toggle/{id}', 'PaymentOptionsController@toggle');

//Areas
Route::get('drivers/show', 'DriversController@home');
Route::get('drivers/edit/{id}', 'DriversController@edit');
Route::get('drivers/toggle/{id}', 'DriversController@toggle');
Route::post('drivers/insert', 'DriversController@insert');
Route::post('drivers/update', 'DriversController@update');

//Areas
Route::get('areas/show', 'AreasController@home');
Route::get('areas/edit/{id}', 'AreasController@edit');
Route::get('areas/toggle/{id}', 'AreasController@toggle');
Route::post('areas/insert', 'AreasController@insert');
Route::post('areas/update', 'AreasController@update');

//Tags
Route::get('rawmaterials/entry/new', 'RawMaterialsController@entry');
Route::get('rawmaterials/stock', 'RawMaterialsController@stock');
Route::post('rawmaterials/entry/insert', 'RawMaterialsController@insertEntry');
Route::get('rawmaterials/show', 'RawMaterialsController@home');
Route::get('rawmaterials/edit/{id}', 'RawMaterialsController@edit');
Route::post('rawmaterials/insert', 'RawMaterialsController@insert');
Route::post('rawmaterials/update', 'RawMaterialsController@update');



//Categories
Route::get('categories/show', 'CategoriesController@home');
Route::get('categories/edit/{id}', 'CategoriesController@editCategory');
Route::get('subcategories/edit/{id}', 'CategoriesController@editSubCategory');
Route::post('categories/insert', 'CategoriesController@insertCategory');
Route::post('subcategories/insert', 'CategoriesController@insertSubCategory');
Route::post('categories/update', 'CategoriesController@updateCategory');
Route::post('subcategories/update', 'CategoriesController@updateSubCategory');


//Dashboard users
Route::get("dash/users/all", 'DashUsersController@index');
Route::post("dash/users/insert", 'DashUsersController@insert');
Route::get("dash/users/edit/{id}", 'DashUsersController@edit');
Route::post("dash/users/update", 'DashUsersController@update');

//Suppliers users
Route::get("suppliers/show/all", 'SuppliersController@home');
Route::get("suppliers/add", 'SuppliersController@add');
Route::post("suppliers/insert", 'SuppliersController@insert');
Route::get("suppliers/edit/{id}", 'SuppliersController@edit');
Route::get("suppliers/profile/{id}", 'SuppliersController@profile');
Route::post("suppliers/update", 'SuppliersController@update');
Route::post("suppliers/pay", 'SuppliersController@pay');


Route::post('api/get/product/prices', 'ApiController@getProductPrices');


Route::get('logout', 'HomeController@logout')->name('logout');
Route::get('/login', 'HomeController@login')->name('login');
Route::post('/login', 'HomeController@authenticate')->name('login');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index')->name('home');

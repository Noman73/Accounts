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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
// banks route
Route::get('/admin/banks', 'BankController@bankForm');
Route::post('/admin/banks', 'BankController@insertBank');
Route::get('/admin/all_banks', 'BankController@allBanks');
Route::get('/admin/test','BankController@test');
// Employee routes
Route::get('/admin/employee', 'EmployeeController@ManageEmployee');
Route::get('/admin/employee/{id}', 'EmployeeController@getEmployee');
Route::post('/admin/employee/{id}', 'EmployeeController@Update');
Route::post('/admin/employee', 'EmployeeController@insertEmployee');
Route::delete('/admin/employee/{id}', 'EmployeeController@Delete');
// Supplier Route
Route::get('/admin/supplier', 'SupplierController@ManageSupplier');
Route::post('/admin/supplier', 'SupplierController@insertSupplier');
Route::delete('/admin/supplier/{id}', 'SupplierController@DeleteSupplier');
Route::get('/admin/get-supplier/{id}', 'SupplierController@getSupplier');
Route::post('/admin/supplier/{id}', 'SupplierController@UpdateSupplier');
Route::post('/admin/search_supplier', 'SupplierController@searchSupplier');
// customer route
Route::get('/admin/customer', 'CustomerController@CustomerForm');
Route::post('/admin/customer', 'CustomerController@CreateNew');
Route::post('/admin/search_customer', 'CustomerController@searchCustomer');
Route::get('/admin/customer_balance/{id?}','CustomerController@getBalance');
Route::delete('/admin/customer/{id?}','CustomerController@Delete');
Route::get('/admin/all-customer','CustomerController@getAll');
Route::get('/admin/get-customer/{id}','CustomerController@getCustomer');
Route::post('/admin/customer/{id}','CustomerController@Update');
//category route
Route::get('/admin/category', 'CategoryController@ManageCategory');
Route::post('/admin/category', 'CategoryController@insertCategory');
Route::get('/admin/get_all_category', 'CategoryController@getCat');
//Child category route
Route::get('/admin/child_category','ChildCategoryController@ManageCategory');
Route::get('/admin/get_child_cat/{id}','ChildCategoryController@getChildCat');
Route::get('/admin/get_all_child_category','ChildCategoryController@allChildCat');
Route::post('/admin/child_category','ChildCategoryController@insertCategory');

// product routes
Route::get('/admin/product','ProductController@ManageProduct');
Route::post('/admin/product','ProductController@insertProduct');
Route::get('/admin/product_price_by_id/{id?}','ProductController@ProductPrice');
Route::get('/admin/product_by_cat/{id}','ProductController@getProduct');
Route::get('/admin/product_by_id/{id}','ProductController@getProductById');
Route::delete('/admin/product/{id}','ProductController@Delete');
Route::post('/admin/product/{id}','ProductController@Update');
Route::post('/admin/product_code','ProductController@productBarcode');
// productType Routes
Route::get('/admin/product_type','ProductTypeController@ManageProductType');
Route::delete('/admin/product_type/{id}','ProductTypeController@Delete');
Route::get('/admin/product_type/{id}','ProductTypeController@getPtype');
Route::post('/admin/product_type','ProductTypeController@insertProductType');
Route::post('/admin/product_type/{id}','ProductTypeController@Update');
// purchase route
Route::get('/admin/purchase','PurchaseController@ManagePurchase');
Route::post('/admin/purchase','PurchaseController@insertPurchase');

// purchase return 
Route::get('admin/purchase_return','PurchaseReturn@PurchaseForm');
Route::post('admin/purchase_return','PurchaseReturn@insertReturn');
// name route
Route::get('/admin/name','NameController@ManageName');
Route::post('/admin/name','NameController@insertName');
// name relation route here
Route::get('/admin/name_relation','NameRelationController@ManageNameRelation');
Route::post('/admin/name_relation','NameRelationController@insertNameRelation');
// voucer controller
Route::get('/admin/voucer','VoucerController@ManageVoucer');
Route::post('/admin/voucer','VoucerController@insertVoucer');
Route::get('/admin/voucer_get_name/{id}','VoucerController@getNameData');

// invoice route
Route::get('/admin/invoice','InvoiceController@invoiceForm');
Route::get('/admin/invoice-update/{id}','InvoiceController@UpdateForm');
Route::post('/admin/invoice','InvoiceController@insertInvoice');
Route::post('/admin/invoice/{id}','InvoiceController@Update');
Route::get('/admin/get_child_cat_by_cat_id/{id?}','InvoiceController@getChildCat');
Route::get('/admin/all-invoices','InvoiceController@allInvoices');
// invoieBack Route
Route::get('/admin/invoice_back','InvoiceBackController@InvoiceBackForm');
Route::post('/admin/invoice_back','InvoiceBackController@insert');


// Running Total Route
Route::get('/admin/running-total','RunningTotalController@Form');
Route::post('/admin/running-total','RunningTotalController@CreateRunningTotal');

// sales report route
Route::get('/admin/sales_report','SalesReportController@Form');
Route::post('/admin/sales_report','SalesReportController@SalesReport');
Route::get('admin/invoice_summery','InvoiceSummeryReport@Form');
Route::post('admin/invoice_summery','InvoiceSummeryReport@Report');
Route::get('admin/daily_statement','DailyStatementController@Form');
Route::post('admin/daily_statement','DailyStatementController@Report');

// info controller route
Route::get('admin/info_form','InfoController@Form');
Route::post('admin/add_info/{id}','InfoController@Insert');
// barcode Route
Route::get('admin/barcode','BarcodeController@Form');
Route::post('admin/barcode','BarcodeController@Generate');
// backup Controller Route
Route::get('admin/backup','BackupController@Form');
Route::get('admin/get_filename','BackupController@FileName');
Route::get('admin/backup-delete/{data}','BackupController@Delete');
Route::get('admin/backup-db','BackupController@Backup');
Route::get('admin/backup-download/{data}','BackupController@Download');
// stock controller
Route::get('admin/stock','StockController@Stock');
// test controller route
Route::get('admin/testpage','TestController@page');
Route::post('admin/testpage','TestController@test');
Route::post('admin/select2','TestController@select2');
Route::get('admin/array','TestController@array');






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
Route::group(['middleware' => ['permission:edit']], function () {
    Route::post('/admin/banks/{id}', 'BankController@Update');
    Route::post('/admin/employee/{id}', 'EmployeeController@Update');
    Route::post('/admin/supplier/{id}', 'SupplierController@UpdateSupplier');
});
Route::get('/admin/user','UserController@Form');
Route::get('/','Auth\LoginController@showLoginForm');
Route::get('/lisencekey','LisencekeyController@Form');
Route::post('/lisencekey','LisencekeyController@Create')->name('lisence');
Auth::routes();
// home controller
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
Route::get('/admin/getVoucerFormData', 'HomeController@getVoucerFormData');
// banks route
Route::get('/admin/banks', 'BankController@bankForm');
Route::post('/admin/banks', 'BankController@insertBank');

Route::get('/admin/all_banks', 'BankController@allBanks');
Route::get('/admin/test','BankController@test');
Route::get('/admin/get_account','BankController@getAccount');
Route::get('/admin/get_banks/{id}','BankController@getBanksById');
Route::post('/admin/get_banks','BankController@getBanks');
Route::get('/admin/get_balance/{id}','BankController@getBalanceById');

// Employee routes
Route::get('/admin/employee', 'EmployeeController@ManageEmployee');
Route::get('/admin/employee/{id}', 'EmployeeController@getEmployee');

Route::post('/admin/employee', 'EmployeeController@insertEmployee');
Route::delete('/admin/employee/{id}', 'EmployeeController@Delete');
// Supplier Route
Route::get('/admin/supplier', 'SupplierController@ManageSupplier');
Route::post('/admin/supplier', 'SupplierController@insertSupplier');
Route::delete('/admin/supplier/{id}', 'SupplierController@DeleteSupplier');
Route::get('/admin/get-supplier/{id}', 'SupplierController@getSupplier');

Route::post('/admin/search_supplier', 'SupplierController@searchSupplier');
Route::get('/admin/supplier_balance/{id}', 'SupplierController@getBalance');
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
Route::get('/admin/category_get/{id}', 'CategoryController@getCatById');
Route::delete('/admin/category/{id}', 'CategoryController@Delete');
Route::post('/admin/category', 'CategoryController@insertCategory');
Route::post('/admin/category/{id}', 'CategoryController@Update');
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
Route::get('/admin/product_qantity/{product_id}/{store_id}','ProductController@getQantity');
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
// opening stock
Route::get('admin/opening_stock','OpeningStockController@Form');
Route::post('admin/opening_stock','OpeningStockController@Create');
// stock transfer route
Route::get('admin/stock_transfer','StockTransferController@Form');
Route::post('admin/stock_transfer','StockTransferController@Create');

// name route
Route::get('/admin/name','NameController@ManageName');
Route::post('/admin/name','NameController@insertName');
Route::post('/admin/search_name','NameController@searchName');
// name relation route here
Route::get('/admin/name_relation','NameRelationController@ManageNameRelation');
Route::post('/admin/name_relation','NameRelationController@insertNameRelation');
Route::get('/admin/relation_search/{id}','NameRelationController@getRelationById');
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
Route::get('/admin/all_invoice','InvoiceController@allInvoices');
// invoieBack Route
Route::get('/admin/invoice_back','InvoiceBackController@InvoiceBackForm');
Route::post('/admin/invoice_back','InvoiceBackController@insert');


// Running Total Route
Route::get('/admin/running-total','RunningTotalController@Form');
Route::post('/admin/running-total','RunningTotalController@CreateRunningTotal');

// sales report route
Route::get('/admin/sales_summery','SaleSummeryController@Form');
Route::post('/admin/sales_summery','SaleSummeryController@Report');
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
// BuyerReportController
Route::get('admin/buyerlistform','BuyerReportController@Form');
Route::get('admin/getbuyerlist','BuyerReportController@BuyerList');
Route::get('admin/getbuyerbalancesheet','BuyerReportController@BuyerBalanceSheet');
Route::get('admin/getbuyerbalanceform','BuyerReportController@BuyerBlnceForm');
// cash Details controller
Route::get('/admin/cash_details_form','CashDetailsController@Form');
Route::get('/admin/cash_details','CashDetailsController@cashDetails');
// store routes
Route::get('/admin/store','StoreController@Form');
Route::post('/admin/store','StoreController@Create');
Route::post('/admin/get_store','StoreController@getStore');
// fund transter Route
Route::get('/admin/fund_transfer','FundTransferController@Form');
Route::post('/admin/fund_transfer','FundTransferController@Transfer');
// transport route 
Route::get('/admin/transport','TransportController@Form');
Route::post('/admin/transport','TransportController@Create');
Route::post('/admin/transport/{id}','TransportController@Update');
Route::post('/admin/get_transport','TransportController@getTransport');
Route::get('/admin/get_transport/{id}','TransportController@Data');
// custom report route
Route::get('/admin/custom_report','CustomReportController@Form');
Route::post('/admin/custom_report','CustomReportController@Report');
// installment Route 
Route::get('/admin/installment','InstallmentController@Form');
Route::post('/admin/installment','InstallmentController@Create');
// installment status Controller
Route::get('/admin/installment_status','InstallmentStatusController@Form');
Route::get('/admin/installment_status/{id}','InstallmentStatusController@getInvoice');
Route::get('/admin/get_ins_invoice/{id?}','InstallmentStatusController@getInvoice');

// installment pay controller
Route::get('/admin/installment_pay','InstallmentPayController@Form');
Route::post('/admin/installment_pay','InstallmentPayController@Create');
Route::post('/admin/get_ins_invoice','InstallmentPayController@getInsInvoice');
Route::get('/admin/get_ins_ammount/{id}','InstallmentPayController@getInsAmmount');
// installment report controller
Route::get('/admin/installment_report','InstallmentReportController@Form');
Route::get('/admin/get_installment_report/{id?}','InstallmentReportController@getReport');
// test controller route
Route::get('admin/testpage','TestController@page');
Route::post('admin/testpage','TestController@test');
Route::post('admin/select2','TestController@select2');
Route::get('admin/array','TestController@array');
// change password route
Route::get('admin/change_password','ChangePasswordController@Form');
Route::post('admin/change_password','ChangePasswordController@Change');
Route::get('admin/'.md5('time()'),function(){
	return 'md5';
})->name('md5');
// permission Manage Controller
Route::get('admin/manage_role','PermissionManageController@CreateRoleForm');
Route::post('admin/manage_role','PermissionManageController@CreateRole');
Route::get('admin/manage_permission','PermissionManageController@CreatePermissionForm');
Route::post('admin/manage_permission','PermissionManageController@CreatePermission');
Route::get('admin/set_role_has_permission','PermissionManageController@roleHasPermissionForm');
Route::post('admin/set_role_has_permission','PermissionManageController@setRoleHasPermission')->name('roleHasPermission');
Route::get('admin/get_role_has_permission','PermissionManageController@getRoleHasPermission');
Route::get('admin/user_wise_role','PermissionManageController@userWiseRoleForm');
Route::post('admin/user_wise_role','PermissionManageController@userWiseRole');






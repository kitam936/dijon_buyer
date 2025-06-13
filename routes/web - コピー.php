<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DataDownloadController;
use App\Http\Controllers\TestMailController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\MenuController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/





Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')
->middleware('can:admin')
->group(function(){
    Route::get('/data/data_menu', [DataController::class, 'menu'])->name('admin.data.data_menu');
    Route::get('/data', [DataController::class, 'create'])->name('admin.data.create');
    Route::get('data/data_index', [DataController::class, 'index'])->name('admin.data.data_index');
    Route::get('data/brand_index', [DataController::class, 'brand_index'])->name('admin.data.brand_index');
    Route::get('data/unit_index', [DataController::class, 'unit_index'])->name('admin.data.unit_index');
    Route::get('data/hinban_index', [DataController::class, 'hinban_index'])->name('admin.data.hinban_index');
    Route::get('data/sku_index', [DataController::class, 'sku_index'])->name('admin.data.sku_index');
    Route::get('data/col_index', [DataController::class, 'col_index'])->name('admin.data.col_index');
    Route::get('data/size_index', [DataController::class, 'size_index'])->name('admin.data.size_index');
    Route::get('data/area_index', [DataController::class, 'area_index'])->name('admin.data.area_index');
    Route::get('data/company_index', [DataController::class, 'company_index'])->name('admin.data.company_index');
    Route::get('data/shop_index', [DataController::class, 'shop_index'])->name('admin.data.shop_index');
    Route::POST('data/hinban_upsert', [DataController::class, 'hinban_upsert'])->name('admin.data.hinban_upsert');
    Route::POST('data/sku_upsert', [DataController::class, 'sku_upsert'])->name('admin.data.sku_upsert');
    Route::POST('data/col_upsert', [DataController::class, 'col_upsert'])->name('admin.data.col_upsert');
    Route::POST('data/size_upsert', [DataController::class, 'size_upsert'])->name('admin.data.size_upsert');
    Route::POST('data/company_upsert', [DataController::class, 'company_upsert'])->name('admin.data.company_upsert');
    Route::POST('data/unit_upsert', [DataController::class, 'unit_upsert'])->name('admin.data.unit_upsert');
    Route::POST('data/brand_upsert', [DataController::class, 'brand_upsert'])->name('admin.data.brand_upsert');
    Route::get('data/delete_index', [DataController::class, 'delete_index'])->name('admin.data.delete_index');
    Route::delete('sku_destroy', [DataController::class, 'sku_destroy'])->name('admin.data.sku_destroy');
    Route::delete('hinban_destroy', [DataController::class, 'hinban_destroy'])->name('admin.data.hinban_destroy');
    Route::delete('shop_destroy', [DataController::class, 'shop_destroy'])->name('admin.data.shop_destroy');
    Route::delete('shop_destroy_all', [DataController::class, 'shop_destroy_all'])->name('admin.data.shop_destroy_all');
    Route::delete('company_destroy', [DataController::class, 'company_destroy'])->name('admin.data.company_destroy');
    Route::delete('unit_destroy', [DataController::class, 'unit_destroy'])->name('admin.data.unit_destroy');
    Route::delete('brand_destroy', [DataController::class, 'brand_destroy'])->name('admin.data.brand_destroy');
    Route::delete('col_destroy', [DataController::class, 'col_destroy'])->name('admin.data.col_destroy');
    Route::delete('size_destroy', [DataController::class, 'size_destroy'])->name('admin.data.size_destroy');
    Route::get('user_create', [UserController::class, 'create'])->name('admin.user_create');
    Route::POST('user_store', [UserController::class, 'store'])->name('admin.user_store');
    Route::get('user_edit/{user}', [UserController::class, 'edit'])->name('admin.user_edit');
    Route::delete('user_destroy/{user}', [UserController::class, 'user_destroy'])->name('admin.user_destroy');

});

Route::prefix('manager')
->middleware('can:manager-higher')
->group(function(){
    Route::get('role_list', [UserController::class, 'role_list'])->name('role_list');
    Route::get('role_edit/{user}', [UserController::class, 'role_edit'])->name('role_edit');
    Route::get('role_update/{user}', [UserController::class, 'role_update'])->name('role_update');
    Route::post('role_update/{user}', [UserController::class, 'role_update'])->name('role_update');


});

Route::middleware('can:user-higher')
->group(function(){
    Route::get('menu', [MenuController::class, 'menu'])->name('menu');
    Route::get('ac_info', [UserController::class, 'ac_info'])->name('ac_info');
    Route::get('ac_info_edit/{user}', [UserController::class, 'ac_info_edit'])->name('ac_info_edit');
    Route::get('pw_change/{user}', [UserController::class, 'pw_change'])->name('pw_change');
    Route::post('pw_update/{user}', [UserController::class, 'pw_update'])->name('pw_update');
    Route::get('pw_change_admin/{user}', [UserController::class, 'pw_change_admin'])->name('pw_change_admin');
    Route::post('pw_update_admin/{user}', [UserController::class, 'pw_update_admin'])->name('pw_update_admin');
    Route::get('memberlist', [UserController::class, 'memberlist'])->name('memberlist');
    Route::get('member_detail/{user}', [UserController::class, 'show'])->name('member_detail');
    Route::get('member_edit/{user}', [UserController::class, 'edit'])->name('member_edit');
    Route::get('member_update1/{user}', [UserController::class, 'member_update_rs1'])->name('member_update1');
    Route::post('member_update1/{user}', [UserController::class, 'member_update_rs1'])->name('member_update1');
    Route::get('product_index', [ProductController::class, 'index'])->name('product_index');
    Route::get('product_show/{hinban}', [ProductController::class, 'show'])->name('product_show');
    Route::get('product_show0/{hinban}', [ProductController::class, 'show0'])->name('product_show0');
    Route::get('comment_detail/{comment}', [CommentController::class, 'comment_detail'])->name('comment_detail');
    Route::get('comment_create/{report}', [CommentController::class, 'comment_create'])->name('comment_create');
    Route::post('comment_store', [CommentController::class, 'comment_store'])->name('comment_store');
    Route::get('comment_edit/{comment}', [CommentController::class, 'comment_edit'])->name('comment_edit');
    Route::post('comment_update/{comment}', [CommentController::class, 'comment_update'])->name('comment_update');
    Route::delete('comment_destroy/{comment}', [CommentController::class, 'comment_destroy'])->name('comment_destroy');
    Route::get('analysis_index', [AnalysisController::class, 'analysis_index'])->name('analysis_index');
    Route::get('manual_download',[DataDownloadController::class,'manual_download'])->name('manual_download');
    Route::get('cart_index', [CartController::class, 'index'])->name('cart_index');
    Route::get('cart_create', [CartController::class, 'create'])->name('cart_create');
    Route::post('cart_add', [CartController::class, 'add'])->name('cart_add');
    Route::get('cart_edit', [CartController::class, 'edit'])->name('cart_edit');
    Route::put('cart_update/{cart}', [CartController::class, 'update'])->name('cart_update');
    Route::delete('cart_destroy/{cart}', [CartController::class, 'destroy'])->name('cart_destroy');
    Route::post('order_confirm', [OrderController::class, 'confirm'])->name('order_confirm');
    Route::get('order_index', [OrderController::class, 'order_index'])->name('order_index');
    Route::get('order_detail/{order}', [OrderController::class, 'order_detail'])->name('order_detail');
    Route::get('order_edit/{order}', [OrderController::class, 'order_edit'])->name('order_edit');
    Route::post('order_update/{order}', [OrderController::class, 'order_update'])->name('order_update');
    Route::get('order_point_edit/{id}', [OrderController::class, 'order_point_edit'])->name('order_point_edit');
    Route::post('order_point_update/{id}', [OrderController::class, 'order_point_update'])->name('order_point_update');
    Route::delete('order_result_destroy/{id}', [OrderController::class, 'result_destroy'])->name('order_result_destroy');  ##発注削除
    Route::get('order_csv', [DataDownloadController::class, 'orderCSV_download'])->name('order_csv');
    Route::get('order_csv_all', [DataDownloadController::class, 'orderCSV_download_all'])->name('order_csv_all');//一括ダウンロード
    Route::get('order_csv_shop', [DataDownloadController::class, 'orderCSV_download_shop'])->name('order_csv_shop');//一括ダウンロード
    Route::get('order_csv_ws', [DataDownloadController::class, 'orderCSV_download_ws'])->name('order_csv_ws');//一括ダウンロード
    Route::get('image_index', [ImageController::class, 'image_index'])->name('image_index');
    Route::get('image_show/{hinban}', [ImageController::class, 'image_show'])->name('image_show');
    Route::get('image_show2/{hinban}', [ImageController::class, 'image_show2'])->name('image_show2');
    Route::get('sku_image_index', [ImageController::class, 'sku_image_index'])->name('sku_image_index');
    Route::get('sku_image_show/{sku}', [ImageController::class, 'sku_image_show'])->name('sku_image_show');
    Route::get('partner_index', [ShopController::class, 'partner_index'])->name('partner_index');


});


require __DIR__.'/auth.php';

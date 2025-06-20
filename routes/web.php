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
use App\Http\Controllers\VendorController;
use App\Http\Controllers\HinbanController;


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

Route::middleware('auth')->group(function () {
    Route::get('/data/data_menu', [DataController::class, 'menu'])->name('data.data_menu');
    Route::get('/data', [DataController::class, 'create'])->name('data.create');
    Route::get('data/data_index', [DataController::class, 'index'])->name('data.data_index');
    Route::get('data/brand_index', [DataController::class, 'brand_index'])->name('data.brand_index');
    Route::get('data/unit_index', [DataController::class, 'unit_index'])->name('data.unit_index');
    Route::get('data/face_index', [DataController::class, 'face_index'])->name('data.face_index');
    Route::get('data/hinban_index', [DataController::class, 'hinban_index'])->name('data.hinban_index');
    Route::get('hinban/hinban_index2', [DataController::class, 'hinban_index2'])->name('hinban.hinban_index2');
    Route::get('data/user_index', [DataController::class, 'user_index'])->name('data.user_index');
    Route::get('data/sku_index', [DataController::class, 'sku_index'])->name('data.sku_index');
    Route::get('data/col_index', [DataController::class, 'col_index'])->name('data.col_index');
    Route::get('data/size_index', [DataController::class, 'size_index'])->name('data.size_index');
    Route::get('data/area_index', [DataController::class, 'area_index'])->name('data.area_index');
    Route::get('data/vendor_index', [DataController::class, 'vendor_index'])->name('data.vendor_index');
    Route::get('data/shop_index', [DataController::class, 'shop_index'])->name('data.shop_index');
    Route::get('data/predata_index', [DataController::class, 'predata_index'])->name('data.predata_index');
    Route::get('data/predata_create', [DataController::class, 'predata_create'])->name('data.predata_create');
    Route::POST('data/predata_store', [DataController::class, 'predata_store'])->name('data.predata_store');
    Route::POST('data/hinban_upsert', [DataController::class, 'hinban_upsert'])->name('data.hinban_upsert');
    Route::POST('data/sku_upsert', [DataController::class, 'sku_upsert'])->name('data.sku_upsert');
    Route::POST('data/col_upsert', [DataController::class, 'col_upsert'])->name('data.col_upsert');
    Route::POST('data/size_upsert', [DataController::class, 'size_upsert'])->name('data.size_upsert');
    Route::POST('data/vendor_upsert', [DataController::class, 'vendor_upsert'])->name('data.vendor_upsert');
    Route::POST('data/unit_upsert', [DataController::class, 'unit_upsert'])->name('data.unit_upsert');
    Route::POST('data/face_upsert', [DataController::class, 'face_upsert'])->name('data.face_upsert');
    Route::POST('data/brand_upsert', [DataController::class, 'brand_upsert'])->name('data.brand_upsert');
    Route::POST('data/predata_upload', [DataController::class, 'predata_upload'])->name('data.predata_upload');
    Route::get('data/delete_index', [DataController::class, 'delete_index'])->name('data.delete_index');
    Route::delete('sku_destroy', [DataController::class, 'sku_destroy'])->name('data.sku_destroy');
    Route::delete('hinban_destroy', [DataController::class, 'hinban_destroy'])->name('data.hinban_destroy');
    Route::delete('shop_destroy', [DataController::class, 'shop_destroy'])->name('data.shop_destroy');
    Route::delete('shop_destroy_all', [DataController::class, 'shop_destroy_all'])->name('data.shop_destroy_all');
    Route::delete('vendor_destroy', [DataController::class, 'vendor_destroy'])->name('data.vendor_destroy');
    Route::delete('unit_destroy', [DataController::class, 'unit_destroy'])->name('data.unit_destroy');
    Route::delete('face_destroy', [DataController::class, 'face_destroy'])->name('data.face_destroy');
    Route::delete('brand_destroy', [DataController::class, 'brand_destroy'])->name('data.brand_destroy');
    Route::delete('col_destroy', [DataController::class, 'col_destroy'])->name('data.col_destroy');
    Route::delete('size_destroy', [DataController::class, 'size_destroy'])->name('data.size_destroy');
    Route::delete('predata_destroy', [DataController::class, 'predata_destroy'])->name('data.predata_destroy');
    Route::delete('predata_destroy_one/{id}', [DataController::class, 'predata_destroy_one'])->name('data.predata_destroy_one');
    Route::get('rate_menu', [DataController::class, 'rate_menu'])->name('data.rate_menu');
    Route::get('ex_rate_edit/{id}', [DataController::class, 'ex_rate_edit'])->name('data.ex_rate_edit');
    Route::get('cost_rate_edit/{id}', [DataController::class, 'cost_rate_edit'])->name('data.cost_rate_edit');
    Route::post('ex_rate_update/{id}', [DataController::class, 'ex_rate_update'])->name('data.ex_rate_update');
    Route::post('cost_rate_update/{id}', [DataController::class, 'cost_rate_update'])->name('data.cost_rate_update');

    Route::get('user_create', [UserController::class, 'create'])->name('user_create');
    Route::POST('user_store', [UserController::class, 'store'])->name('user_store');
    Route::get('user_edit/{user}', [UserController::class, 'edit'])->name('user_edit');
    Route::delete('user_destroy/{user}', [UserController::class, 'user_destroy'])->name('user_destroy');

    Route::get('hinban_create', [HinbanController::class, 'create'])->name('hinban_create');
    Route::get('hinban_create0', [HinbanController::class, 'create0'])->name('hinban_create0');
    Route::get('hinban_create2', [HinbanController::class, 'create2'])->name('hinban_create2');
    Route::POST('hinban_store', [HinbanController::class, 'store'])->name('hinban_store');
    Route::get('hinban_show/{id}', [HinbanController::class, 'show'])->name('hinban_show');
    Route::get('hinban_show2/{id}', [HinbanController::class, 'show2'])->name('hinban_show2');
    Route::get('hinban_edit/{id}', [HinbanController::class, 'edit'])->name('hinban_edit');
    Route::post('hinban_update/{id}', [HinbanController::class, 'update'])->name('hinban_update');
    Route::delete('hinban_destroy/{id}', [HinbanController::class, 'destroy'])->name('hinban_destroy');
    Route::delete('hinban_destroy_one/{id}', [HinbanController::class, 'destroy_one'])->name('hinban_destroy_one');
    Route::delete('sku_clear/{id}', [HinbanController::class, 'sku_clear'])->name('sku_clear');
    Route::get('hinban_image_index', [HinbanController::class, 'hinban_image_index'])->name('hinban_image_index');
    Route::get('hinban_image_show/{id}', [HinbanController::class, 'hinban_image_show'])->name('hinban_image_show');

    Route::get('role_list', [UserController::class, 'role_list'])->name('role_list');
    Route::get('role_edit/{user}', [UserController::class, 'role_edit'])->name('role_edit');
    Route::get('role_update/{user}', [UserController::class, 'role_update'])->name('role_update');
    Route::post('role_update/{user}', [UserController::class, 'role_update'])->name('role_update');

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
    Route::get('comment_create/{hinban}', [CommentController::class, 'comment_create'])->name('comment_create');
    Route::post('comment_store', [CommentController::class, 'comment_store'])->name('comment_store');
    Route::get('comment_edit/{comment}', [CommentController::class, 'comment_edit'])->name('comment_edit');
    Route::post('comment_update/{comment}', [CommentController::class, 'comment_update'])->name('comment_update');
    Route::delete('comment_destroy/{comment}', [CommentController::class, 'comment_destroy'])->name('comment_destroy');

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
    Route::get('order_image_list', [OrderController::class, 'order_image_list'])->name('order_image_list');

    // Route::get('image_index', [ImageController::class, 'image_index'])->name('image_index');
    // Route::get('image_show/{hinban}', [ImageController::class, 'image_show'])->name('image_show');
    // Route::get('image_show2/{hinban}', [ImageController::class, 'image_show2'])->name('image_show2');
    // Route::get('sku_image_index', [ImageController::class, 'sku_image_index'])->name('sku_image_index');
    // Route::get('sku_image_show/{sku}', [ImageController::class, 'sku_image_show'])->name('sku_image_show');

    Route::get('vendor_index', [VendorController::class, 'index'])->name('vendor_index');
    Route::get('vendor_show/{id}', [VendorController::class, 'show'])->name('vendor_show');
    Route::get('vendor_create', [VendorController::class, 'create'])->name('vendor_create');
    Route::post('vendor_store', [VendorController::class, 'vendor_store'])->name('vendor_store');
    Route::get('vendor_edit/{id}', [VendorController::class, 'edit'])->name('vendor_edit');
    Route::put('vendor_update/{id}', [VendorController::class, 'update'])->name('vendor_update');
    Route::delete('vendor_destroy/{id}', [VendorController::class, 'destroy'])->name('vendor_destroy');

});


require __DIR__.'/auth.php';

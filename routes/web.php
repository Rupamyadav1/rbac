<?php

use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\RegisterAdminController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLoginController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [MenuController::class, 'home'])->name('home');

Route::get('/products', [MenuController::class, 'products'])->name('products');

Route::get('/about-us', [MenuController::class, 'about'])->name('about');

Route::get('/product-detail/{slug}', [MenuController::class, 'productDetail'])
    ->name('product-detail');

Route::get('/admin/login', function () {
    return view('auth.login');
})->middleware('guest:admin')->name('admin.login');


Route::get('admin/register', function () {
    return view('auth.register');
})->middleware('guest:admin')->name('admin.register');


Route::get('/login', function () {
    return view('login');
})->name('login');


Route::get('/register', function () {
    return view('register');
})->middleware('guest:admin')->name('register');


Route::post('register/send-otp', [RegisterUserController::class, 'sendOtp'])
    ->name('register.sendOtp');


Route::post('register/verify-otp', [RegisterUserController::class, 'verifyOtp'])
    ->name('register.verifyOtp');



Route::post('user/login/send-otp', [UserLoginController::class, 'sendOtp'])
    ->name('user.login.sendOtp');

Route::post('user/login/verify-otp', [UserLoginController::class, 'verifyOtp'])
    ->name('user.login.verifyOtp');

Route::post('admin/login/send-otp', [LoginController::class, 'sendOtp'])
    ->name('admin.login.sendOtp');

Route::post('admin/login/verify-otp', [LoginController::class, 'verifyOtp'])
    ->name('admin.login.verifyOtp');
/*
|--------------------------------------------------------------------------
| Admin Registration (OTP)
|--------------------------------------------------------------------------
*/

Route::post('admin/register/send-otp', [RegisterAdminController::class, 'sendOtp'])
    ->name('admin.register.sendOtp');

Route::post('admin/register/verify-otp', [RegisterAdminController::class, 'verifyOtp'])
    ->name('admin.register.verifyOtp');




Route::middleware(['user.auth'])->group(function () {
    Route::get('/user/dashboard', function () {
        return view('users.dashboard');
    })->name('user.dashboard');
    Route::post('/buy-now', [UserController::class, 'buyNow'])
        ->name('user.buyNow');

          Route::get('/myorder', [UserController::class, 'myorder'])
        ->name('user.myorder');

    Route::get('/user/products', [UserController::class, 'products'])->name('user.product');

    Route::post('/logout', function () {
        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    })->name('user.logout');
});
Route::middleware(['admin.auth'])->group(function () {
    Route::post('/admin/logout', function () {
        Auth::guard('admin')->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('admin.login');
    })->name('admin.logout');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('admin.dashboard');



    Route::get('/sections', [SectionController::class, 'index'])->name('section.index')->middleware('permission:view cms');
    Route::get('/sections/create', [SectionController::class, 'create'])->name('section.create')->middleware('permission:create cms');

    Route::post('/section/store', [SectionController::class, 'store'])->name('section.store');
    Route::get('/sections/edit/{id}', [SectionController::class, 'edit'])->name('section.edit')->middleware('permission:edit cms');
    Route::post('/section/update/{id}', [SectionController::class, 'update'])->name('section.update');


    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/edit/{permissionId}', [PermissionController::class, 'edit'])->name('permission.edit');
    Route::post('/permissions/update/{permissionId}', [PermissionController::class, 'update'])->name('permission.update');
    Route::delete('/permissions', [PermissionController::class, 'destroy'])->name('permission.destroy');

    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/edit/{roleId}', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('/roles/update/{roleId}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles', [RoleController::class, 'destroy'])->name('role.destroy');

    Route::get('/users', [AdminController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminController::class, 'store'])->name('users.store');
    Route::get('/users/edit/{userId}', [AdminController::class, 'edit'])->name('users.edit');
    Route::post('/user/update/{userId}', [AdminController::class, 'update'])->name('user.update');
    Route::delete('/user', [AdminController::class, 'destroy'])->name('user.destroy');

    Route::get('product/index', [ProductController::class, 'index'])->name('product.index')->middleware('permission:view products');
    Route::get('product/create', [ProductController::class, 'create'])->name('product.create')->middleware('permission:create products');
    Route::post('product/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit')->middleware('permission:edit products');
    Route::post('product/update/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('product/delete/{id}', [ProductController::class, 'destroy'])->name('product.destroy')->middleware('permission:delete products');

    Route::post('/admin/product/upload-temp-image',
    [ProductController::class, 'uploadTempImage']
)->name('product.upload.temp');

    Route::get('/products/export/csv', [ProductController::class, 'exportCsv'])
        ->name('products.export.csv');

    Route::post('/admin/products/upload-image', [ProductController::class, 'uploadImage'])
        ->name('product.upload.image');

    Route::delete('/admin/products/delete-image/{id}', [ProductController::class, 'deleteImage'])
        ->name('product.image.delete');

    Route::get('category/index', [CategoryController::class, 'index'])->name('category.index')->middleware('permission:view category');
    Route::get('category/create', [CategoryController::class, 'create'])->name('category.create')->middleware('permission:create category');
    Route::post('category/store', [CategoryController::class, 'store'])->name('category.store');
    Route::get('category/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit')->middleware('permission:edit category');
    Route::post('category/update/{id}', action: [CategoryController::class, 'update'])->name('category.update');
    Route::delete('category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy')->middleware('permission:delete category');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

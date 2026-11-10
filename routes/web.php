<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CompanySettingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Frontend\CategoryController as FrontendCategoryController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// Serve uploaded images (products, categories, gallery...) even when the
// public/storage link has not been created with `php artisan storage:link`.
// When the link exists, the web server serves the files directly and this route is skipped.
Route::get('/storage/{path}', function (string $path) {
    $disk = Storage::disk('public');
    abort_unless($disk->exists($path), 404);

    return response()->file($disk->path($path), ['Cache-Control' => 'public, max-age=604800']);
})->where('path', '.*')->name('storage.fallback');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about-us', [HomeController::class, 'about'])->name('about');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/gallery', [HomeController::class, 'gallery'])->name('gallery');
Route::get('/contact-us', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact-us', [ContactController::class, 'store'])->name('contact.store');

Route::get('/products', [FrontendProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [FrontendProductController::class, 'show'])->name('products.show');
Route::get('/categories', [FrontendCategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [FrontendCategoryController::class, 'show'])->name('categories.show');
Route::get('/enquiry/submit/{product?}', [FrontendProductController::class, 'enquiryForm'])->name('enquiry.form');
Route::post('/enquiry/submit', [FrontendProductController::class, 'storeEnquiry'])->name('enquiry.store');
Route::get('/qr-enquiry', [FrontendProductController::class, 'qrEnquiryForm'])->name('enquiry.qr');
Route::get('/enquiry/success', [FrontendProductController::class, 'success'])->name('enquiry.success');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('/categories', CategoryController::class);
        Route::resource('/subcategories', SubcategoryController::class);
        Route::resource('/products', AdminProductController::class);
        Route::get('/qr-enquiries', [EnquiryController::class, 'qr'])->name('enquiries.qr');
        Route::get('/qr-enquiries/export/excel', [EnquiryController::class, 'qrExportExcel'])->name('enquiries.qr.excel');
        Route::get('/qr-enquiries/export/pdf', [EnquiryController::class, 'qrExportPdf'])->name('enquiries.qr.pdf');
        Route::get('/enquiries', [EnquiryController::class, 'index'])->name('enquiries.index');
        Route::get('/enquiries/{enquiry}', [EnquiryController::class, 'show'])->name('enquiries.show');
        Route::post('/enquiries/{enquiry}/status', [EnquiryController::class, 'updateStatus'])->name('enquiries.updateStatus');
        Route::delete('/enquiries/{enquiry}', [EnquiryController::class, 'destroy'])->name('enquiries.destroy');
        Route::resource('/banners', BannerController::class);
        Route::resource('/gallery', GalleryController::class);
        Route::get('/settings', [CompanySettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [CompanySettingController::class, 'update'])->name('settings.update');
        Route::get('/profile', [CompanySettingController::class, 'profile'])->name('profile');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});

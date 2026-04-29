<?php

// routes/web.php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeliveryZoneController;
use App\Http\Controllers\Admin\EmailController as AdminEmailController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

/* ---------- Public site ---------- */

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{product:slug}', [MenuController::class, 'show'])->name('menu.show');

// Cart (form + JSON)
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{product:id}', [CartController::class, 'add'])->name('add');
    Route::patch('/update/{product:id}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{product:id}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');

    // Coupons
    Route::post('/coupon', [CouponController::class, 'apply'])->name('coupon.apply');
    Route::delete('/coupon', [CouponController::class, 'remove'])->name('coupon.remove');
});

// Wishlist
Route::prefix('wishlist')->name('wishlist.')->group(function () {
    Route::get('/', [WishlistController::class, 'index'])->name('index');
    Route::post('/toggle/{product:id}', [WishlistController::class, 'toggle'])->name('toggle');
    Route::post('/move/{product:id}', [WishlistController::class, 'moveToCart'])->name('move');
});

// Checkout
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/', [CheckoutController::class, 'store'])->name('store');
    Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
});

Route::get('/checkout/delivery-fee', [CheckoutController::class, 'getDeliveryFee'])->name('checkout.delivery-fee');

// Reviews (auth only)
Route::middleware('auth')->group(function () {
    Route::post('/reviews/{product:id}', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Static Pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactStore'])->name('contact.store');

/* ---------- Auth ---------- */
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/* ---------- Profile ---------- */
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::get('/orders', [ProfileController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [ProfileController::class, 'showOrder'])->name('orders.show');
    Route::get('/orders/{order}/print', [ProfileController::class, 'printInvoice'])->name('orders.print');
    Route::patch('/update', [ProfileController::class, 'update'])->name('update');
    Route::patch('/password', [ProfileController::class, 'updatePassword'])->name('password');

    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::patch('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::patch('/addresses/{address}/default', [AddressController::class, 'setDefault'])->name('addresses.default');
});

/* ---------- Admin ---------- */
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // POS
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/order', [PosController::class, 'storeOrder'])->name('pos.store');
    Route::get('/pos/search', [PosController::class, 'searchProducts'])->name('pos.search');
    Route::get('/pos/product/{id}', [PosController::class, 'getProduct'])->name('pos.product');

    Route::get('products/trash', [AdminProductController::class, 'trash'])->name('products.trash');
    Route::patch('products/{id}/restore', [AdminProductController::class, 'restore'])->name('products.restore');
    Route::delete('products/{id}/force', [AdminProductController::class, 'forceDelete'])->name('products.force');
    Route::resource('products', AdminProductController::class)->except('show');
    Route::patch('products/{product}/toggle-status', [AdminProductController::class, 'toggleStatus'])
        ->name('products.toggle-status');
    Route::post('/products/update-home-order', [AdminProductController::class, 'updateHomeOrder'])
        ->name('products.update-home-order');

    // Add the new home manager page route
    Route::get('/products/home-manager', [AdminProductController::class, 'homeManager'])
        ->name('products.home-manager');

    Route::get('categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::patch('categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('/orders/{id}/print', [AdminOrderController::class, 'printInvoice'])->name('orders.print');

    Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
    Route::patch('users/{user}/admin', [AdminUserController::class, 'toggleAdmin'])->name('users.admin');
    Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::patch('settings', [SettingsController::class, 'update'])->name('settings.update');

    Route::get('contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');
    Route::delete('contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

    Route::get('delivery-zones', [DeliveryZoneController::class, 'index'])->name('delivery-zones.index');
    Route::post('delivery-zones', [DeliveryZoneController::class, 'store'])->name('delivery-zones.store');
    Route::patch('delivery-zones/{zone}', [DeliveryZoneController::class, 'update'])->name('delivery-zones.update');
    Route::delete('delivery-zones/{zone}', [DeliveryZoneController::class, 'destroy'])->name('delivery-zones.destroy');

    // Coupons
    Route::get('coupons', [AdminCouponController::class, 'index'])->name('coupons.index');
    Route::post('coupons', [AdminCouponController::class, 'store'])->name('coupons.store');
    Route::patch('coupons/{coupon}', [AdminCouponController::class, 'update'])->name('coupons.update');
    Route::delete('coupons/{coupon}', [AdminCouponController::class, 'destroy'])->name('coupons.destroy');

    Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::patch('reviews/{review}/toggle', [AdminReviewController::class, 'toggle'])->name('reviews.toggle');
    Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('email-templates', [EmailTemplateController::class, 'index'])->name('email-templates.index');
    Route::get('email-templates/create', [EmailTemplateController::class, 'create'])->name('email-templates.create');
    Route::post('email-templates', [EmailTemplateController::class, 'store'])->name('email-templates.store');
    Route::get('email-templates/{template}/edit', [EmailTemplateController::class, 'edit'])->name('email-templates.edit');
    Route::patch('email-templates/{template}', [EmailTemplateController::class, 'update'])->name('email-templates.update');
    Route::delete('email-templates/{template}', [EmailTemplateController::class, 'destroy'])->name('email-templates.destroy');

    Route::get('emails/send', [AdminEmailController::class, 'create'])->name('emails.send');
    Route::post('emails/send', [AdminEmailController::class, 'send'])->name('emails.send.store');
    Route::get('emails/history', [AdminEmailController::class, 'history'])->name('emails.history');

    // Advertisements
    Route::get('advertisements', [\App\Http\Controllers\Admin\AdvertisementController::class, 'index'])->name('advertisements.index');
    Route::post('advertisements', [\App\Http\Controllers\Admin\AdvertisementController::class, 'store'])->name('advertisements.store');
    Route::patch('advertisements/{advertisement}', [\App\Http\Controllers\Admin\AdvertisementController::class, 'update'])->name('advertisements.update');
    Route::patch('advertisements/{advertisement}/toggle', [\App\Http\Controllers\Admin\AdvertisementController::class, 'toggle'])->name('advertisements.toggle');
    Route::delete('advertisements/{advertisement}', [\App\Http\Controllers\Admin\AdvertisementController::class, 'destroy'])->name('advertisements.destroy');
});

Route::get('/order/track', [OrderTrackingController::class, 'showForm'])->name('order.track.form');
Route::post('/order/track', [OrderTrackingController::class, 'track'])->name('order.track');
Route::get('/order/{invoice_no}/status', [OrderTrackingController::class, 'status'])->name('order.status');

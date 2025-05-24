<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SettingController;

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

// Маршруты аутентификации
Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout')
    ->middleware('auth');

// Защищенные маршруты
Route::middleware(['auth:sanctum', 'auth'])->group(function () {
    // Общие маршруты для всех авторизованных пользователей
    Route::get('/', function () {
        return view('dashboard');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Маршрут для получения данных графика активности
    Route::get('/dashboard-stats', [ReportController::class, 'getDashboardStats']);

    Route::get('/help', function () {
        return view('help');
    })->name('help');

    Route::get('/profile', function () {
        return view('profile');
    })->name('profile.show');

    // Маршруты для работников склада
    Route::middleware(['role:Employee'])->group(function () {
        Route::resource('materials', MaterialController::class);
        Route::post('departments/{department}/distribute', [MaterialController::class, 'distribute'])->name('material.distribute');
        Route::resource('purchase-requests', PurchaseRequestController::class)->except(['edit', 'update', 'destroy', 'approve', 'reject', 'unarchive']);
        Route::resource('departments', DepartmentController::class);
        
        Route::post('purchase-requests/{purchaseRequest}/archive', [PurchaseRequestController::class, 'archive'])->name('purchase-requests.archive');
        Route::get('purchase-requests/archived', [PurchaseRequestController::class, 'archived'])->name('purchase-requests.archived');
    });

    // Маршруты для заведующего складом
    Route::middleware(['role:Manager'])->group(function () {
        Route::resource('suppliers', SupplierController::class);
        Route::resource('contracts', \App\Http\Controllers\ContractController::class);
        Route::resource('orders', OrderController::class)->except(['updateStatus']);
        
        Route::get('/reports/budget', [ReportController::class, 'budget'])->name('reports.budget');
        Route::get('/reports/requests', [ReportController::class, 'requests'])->name('reports.requests');
        Route::get('/reports/suppliers', [ReportController::class, 'suppliers'])->name('reports.suppliers');
        
        Route::get('orders/archived', [OrderController::class, 'archived'])->name('orders.archived');
        
        Route::get('departments/{department}/norms', [ReportController::class, 'monthlyNorms'])->name('departments.norms');
        
        Route::post('purchase-requests/{purchaseRequest}/approve', [PurchaseRequestController::class, 'approve'])->name('purchase-requests.approve');
        Route::post('purchase-requests/{purchaseRequest}/reject', [PurchaseRequestController::class, 'reject'])->name('purchase-requests.reject');
        
        Route::post('orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('orders/{order}/archive', [OrderController::class, 'archive'])->name('orders.archive');
    });

    // Маршруты только для администраторов
    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/admin', function () {
            return view('admin');
        })->name('admin.panel');

        Route::resource('budgets', \App\Http\Controllers\BudgetController::class);
        Route::resource('users', \App\Http\Controllers\UserController::class);
        Route::resource('roles', RoleController::class);
        
        Route::get('/reports/monthly-norms', [ReportController::class, 'monthlyNorms'])->name('reports.monthly-norms');
        
        Route::get('users/export', [\App\Http\Controllers\UserController::class, 'export'])->name('users.export');
        Route::get('users/{user}/change-password', [\App\Http\Controllers\UserController::class, 'changePasswordForm'])->name('users.change-password-form');
        Route::post('users/{user}/change-password', [\App\Http\Controllers\UserController::class, 'changePassword'])->name('users.change-password');

        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
    });

    // Общие маршруты для уведомлений
    Route::middleware(['auth'])->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/read/{id}', [NotificationController::class, 'read'])->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.readAll');
    });
});

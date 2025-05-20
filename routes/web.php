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
Route::middleware([
    'auth:sanctum',
    'auth'
])->group(function () {
Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    // Маршруты, доступные только администраторам
    Route::middleware(['admin'])->group(function () {
        Route::resource('materials', MaterialController::class);
        Route::resource('budgets', \App\Http\Controllers\BudgetController::class);
        Route::resource('users', \App\Http\Controllers\UserController::class);
        // Только админ может утверждать/отклонять/архивировать заявки и заказы
        Route::post('purchase-requests/{purchaseRequest}/approve', [PurchaseRequestController::class, 'approve'])->name('purchase-requests.approve');
        Route::post('purchase-requests/{purchaseRequest}/reject', [PurchaseRequestController::class, 'reject'])->name('purchase-requests.reject');
        Route::post('purchase-requests/{purchaseRequest}/archive', [PurchaseRequestController::class, 'archive'])->name('purchase-requests.archive');
        Route::post('purchase-requests/{purchaseRequest}/unarchive', [PurchaseRequestController::class, 'unarchive'])->name('purchase-requests.unarchive');
        Route::post('orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('orders/{order}/archive', [OrderController::class, 'archive'])->name('orders.archive');
        Route::post('orders/{order}/unarchive', [OrderController::class, 'unarchive'])->name('orders.unarchive');
        Route::get('suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
        Route::post('suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::get('suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
        Route::put('suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
        Route::get('contracts/create', [\App\Http\Controllers\ContractController::class, 'create'])->name('contracts.create');
        Route::post('contracts', [\App\Http\Controllers\ContractController::class, 'store'])->name('contracts.store');
        Route::get('contracts/{contract}/edit', [\App\Http\Controllers\ContractController::class, 'edit'])->name('contracts.edit');
        Route::put('contracts/{contract}', [\App\Http\Controllers\ContractController::class, 'update'])->name('contracts.update');
        Route::delete('contracts/{contract}', [\App\Http\Controllers\ContractController::class, 'destroy'])->name('contracts.destroy');
        Route::get('users/export', [\App\Http\Controllers\UserController::class, 'export'])->name('users.export');
        Route::get('users/{user}/change-password', [\App\Http\Controllers\UserController::class, 'changePasswordForm'])->name('users.change-password-form');
        Route::post('users/{user}/change-password', [\App\Http\Controllers\UserController::class, 'changePassword'])->name('users.change-password');
    });

    // Просмотр архива заявок — для всех авторизованных
    Route::get('purchase-requests/archived', [PurchaseRequestController::class, 'archived'])->name('purchase-requests.archived');

    // Просмотр архива заказов — для всех авторизованных
    Route::get('orders/archived', [OrderController::class, 'archived'])->name('orders.archived');

    // Страница профиля
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile.show');

    Route::get('/admin', function () {
        abort_unless(auth()->user() && auth()->user()->hasRole('admin'), 403);
        return view('admin');
    })->name('admin.panel');

    Route::get('/help', function () {
        return view('help');
    })->name('help');

    Route::get('/dashboard-stats', function () {
        $months = collect(range(0, 5))->map(function($i) {
            return now()->subMonths($i)->format('m.Y');
        })->reverse()->values();
        $requests = $months->map(function($month) {
            [$m, $y] = explode('.', $month);
            return \App\Models\PurchaseRequest::whereYear('created_at', $y)->whereMonth('created_at', $m)->count();
        });
        $orders = $months->map(function($month) {
            [$m, $y] = explode('.', $month);
            return \App\Models\Order::whereYear('created_at', $y)->whereMonth('created_at', $m)->count();
        });
        return response()->json([
            'labels' => $months,
            'requests' => $requests,
            'orders' => $orders,
        ]);
    });

    // Маршруты для управления ролями
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::resource('roles', RoleController::class);
    });

    Route::get('/reports/budget', [ReportController::class, 'budget'])->name('reports.budget');
    Route::get('/reports/requests', [ReportController::class, 'requests'])->name('reports.requests');
    Route::get('/reports/suppliers', [ReportController::class, 'suppliers'])->name('reports.suppliers');

    // Эти разделы доступны всем авторизованным (employee, manager, admin)
    Route::resource('suppliers', SupplierController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('contracts', \App\Http\Controllers\ContractController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('purchase-requests', PurchaseRequestController::class)->except(['approve', 'reject', 'archive', 'unarchive']);
    Route::resource('orders', OrderController::class)->except(['archive', 'unarchive', 'updateStatus']);

    // Просмотр поставщиков — для всех авторизованных
    Route::get('suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');

    // Просмотр контрактов — для всех авторизованных
    Route::get('contracts', [\App\Http\Controllers\ContractController::class, 'index'])->name('contracts.index');
    Route::get('contracts/{contract}', [\App\Http\Controllers\ContractController::class, 'show'])->name('contracts.show');

    Route::middleware(['auth'])->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/read/{id}', [NotificationController::class, 'read'])->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.readAll');
    });
});

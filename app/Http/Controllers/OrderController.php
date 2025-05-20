<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Notifications\SystemEventNotification;
use App\Models\User;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with(['supplier', 'purchaseRequest'])
            ->notArchived()
            ->latest()
            ->paginate(10);
            
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $purchaseRequests = \App\Models\PurchaseRequest::where('status', 'approved')->get();
        return view('orders.create', compact('suppliers', 'purchaseRequests'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'expected_delivery_date' => 'required|date|after:today',
            'payment_terms' => 'required|string|max:1000',
            'shipping_address' => 'required|string|max:1000',
            'notes' => 'nullable|string|max:1000',
            'purchase_request_id' => 'required|exists:purchase_requests,id',
            'unit_price' => 'required|numeric|min:0.01',
        ]);

        $purchaseRequest = \App\Models\PurchaseRequest::findOrFail($validated['purchase_request_id']);
        $quantity = $purchaseRequest->quantity;
        $unitPrice = $validated['unit_price'];
        $totalAmount = $quantity * $unitPrice;

        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'supplier_id' => $validated['supplier_id'],
            'purchase_request_id' => $validated['purchase_request_id'],
            'expected_delivery_date' => $validated['expected_delivery_date'],
            'payment_terms' => $validated['payment_terms'],
            'shipping_address' => $validated['shipping_address'],
            'notes' => $validated['notes'],
            'status' => 'pending',
            'created_by' => auth()->id(),
            'total_amount' => $totalAmount,
        ]);

        // Уведомление инициатору
        $order->creator->notify(new SystemEventNotification(
            'Заказ создан',
            'Ваш заказ №' . $order->order_number . ' успешно создан.',
            route('orders.archived')
        ));
        // Уведомление админам
        foreach (User::where('role', 'admin')->get() as $admin) {
            $admin->notify(new SystemEventNotification(
                'Создан новый заказ',
                'Пользователь ' . $order->creator->name . ' создал новый заказ №' . $order->order_number . '.',
                route('orders.archived')
            ));
        }
        return redirect()
            ->route('orders.index')
            ->with('success', 'Заказ успешно создан');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:confirmed,shipped,delivered,cancelled',
        ]);

        $order->update([
            'status' => $validated['status'],
            'status_updated_at' => now(),
            'status_updated_by' => auth()->id(),
        ]);

        // Уведомление инициатору
        $order->creator->notify(new SystemEventNotification(
            'Статус заказа изменён',
            'Статус вашего заказа №' . $order->order_number . ' изменён на: ' . $validated['status'],
            route('orders.archived')
        ));
        return redirect()
            ->route('orders.index')
            ->with('success', 'Статус заказа успешно обновлен');
    }

    public function archive(Order $order)
    {
        $order->archive();
        // Уведомление инициатору
        $order->creator->notify(new SystemEventNotification(
            'Заказ архивирован',
            'Ваш заказ №' . $order->order_number . ' был архивирован.',
            route('orders.archived')
        ));
        return redirect()->back()->with('success', 'Заказ успешно архивирован');
    }

    public function unarchive(Order $order)
    {
        $order->unarchive();
        // Уведомление инициатору
        $order->creator->notify(new SystemEventNotification(
            'Заказ восстановлен',
            'Ваш заказ №' . $order->order_number . ' был восстановлен из архива.',
            route('orders.archived')
        ));
        return redirect()->back()->with('success', 'Заказ успешно разархивирован');
    }

    public function archived()
    {
        $orders = Order::with(['supplier', 'purchaseRequest'])
            ->archived()
            ->latest()
            ->paginate(10);
        return view('orders.archived', compact('orders'));
    }
}

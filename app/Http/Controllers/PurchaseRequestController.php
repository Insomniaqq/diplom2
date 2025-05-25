<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestStatusLog;
use Illuminate\Http\Request;
use App\Notifications\SystemEventNotification;
use App\Models\User;

class PurchaseRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requests = PurchaseRequest::with(['material', 'requester'])
            ->notArchived()
            ->latest()
            ->paginate(10);
            
        return view('purchase-requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $materials = Material::all();
        return view('purchase-requests.create', compact('materials'));
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
            'material_id' => 'required|exists:materials,id',
            'quantity' => 'required|numeric|min:0.01',
            'justification' => 'required',
        ]);

        $validated['requested_by'] = auth()->id();
        $validated['status'] = 'pending';

        // --- КОНТРОЛЬ БЮДЖЕТА ---
        $material = \App\Models\Material::findOrFail($validated['material_id']);
        if (!isset($material->price)) {
            return redirect()->back()->withInput()->withErrors(['material_id' => 'У выбранного материала не указана цена.']);
        }
        $year = date('Y');
        $month = date('n');
        $amount = $validated['quantity'] * $material->price;
        $budget = \App\Models\Budget::where('year', $year)->where('month', $month)->first();
        if (!$budget) {
            return redirect()->back()->withInput()->withErrors(['budget' => 'Бюджет на выбранный месяц не найден.']);
        }
        if ($budget->spent + $amount > $budget->amount) {
            return redirect()->back()->withInput()->withErrors(['budget' => 'Превышен лимит бюджета на этот месяц.']);
        }
        // --- /КОНТРОЛЬ БЮДЖЕТА ---

        $purchaseRequest = PurchaseRequest::create($validated);
        $budget->spent += $amount;
        $budget->save();
        // Уведомление инициатору
        $purchaseRequest->requester->notify(new SystemEventNotification(
            'Заявка создана',
            'Ваша заявка на закупку успешно создана.',
            route('purchase-requests.show', $purchaseRequest->id)
        ));
        // Уведомление админам
        foreach (User::where('role', 'admin')->get() as $admin) {
            $admin->notify(new SystemEventNotification(
                'Создана новая заявка',
                'Пользователь ' . $purchaseRequest->requester->name . ' создал новую заявку на закупку.',
                route('purchase-requests.show', $purchaseRequest->id)
            ));
        }
        return redirect()->route('purchase-requests.index')
            ->with('success', 'Заявка на закупку успешно создана.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $request = \App\Models\PurchaseRequest::with('material', 'requester')->findOrFail($id);
        $statusLogs = \App\Models\PurchaseRequestStatusLog::with('user')
            ->where('purchase_request_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('purchase-requests.show', compact('request', 'statusLogs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $request = PurchaseRequest::findOrFail($id);
        $materials = \App\Models\Material::all();
        return view('purchase-requests.edit', compact('request', 'materials'));
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
        $purchaseRequest = PurchaseRequest::findOrFail($id);
        $oldStatus = $purchaseRequest->status;
        // Если админ завершает заявку
        if ($request->has('status') && $request->input('status') === 'completed') {
            $purchaseRequest->update(['status' => 'completed']);
            \App\Models\PurchaseRequestStatusLog::create([
                'purchase_request_id' => $purchaseRequest->id,
                'old_status' => $oldStatus,
                'new_status' => 'completed',
                'user_id' => auth()->id(),
            ]);
            // Уведомление инициатору
            $purchaseRequest->requester->notify(new \App\Notifications\SystemEventNotification(
                'Заявка завершена',
                'Ваша заявка на закупку была завершена.',
                route('purchase-requests.show', $purchaseRequest->id)
            ));
            return redirect()->route('purchase-requests.show', $purchaseRequest->id)
                ->with('success', 'Заявка успешно завершена.');
        }
        // Обычное обновление заявки
        $validated = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'quantity' => 'required|numeric|min:0.01',
            'justification' => 'required',
        ]);
        $purchaseRequest->update($validated);
        if (isset($validated['status']) && $validated['status'] !== $oldStatus) {
            \App\Models\PurchaseRequestStatusLog::create([
                'purchase_request_id' => $purchaseRequest->id,
                'old_status' => $oldStatus,
                'new_status' => $validated['status'],
                'user_id' => auth()->id(),
            ]);
        }
        // Уведомление инициатору
        $purchaseRequest->requester->notify(new \App\Notifications\SystemEventNotification(
            'Заявка обновлена',
            'Ваша заявка на закупку была обновлена.',
            route('purchase-requests.show', $purchaseRequest->id)
        ));
        return redirect()->route('purchase-requests.index')
            ->with('success', 'Заявка на закупку успешно обновлена.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $purchaseRequest = PurchaseRequest::findOrFail($id);
        $purchaseRequest->requester->notify(new SystemEventNotification(
            'Заявка удалена',
            'Ваша заявка на закупку была удалена администратором.',
            null
        ));
        $purchaseRequest->delete();
        return redirect()->route('purchase-requests.index')
            ->with('success', 'Заявка на закупку успешно удалена.');
    }

    public function approve(PurchaseRequest $purchaseRequest)
    {
        $this->authorize('approve', $purchaseRequest);

        $oldStatus = $purchaseRequest->status;
        $purchaseRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        PurchaseRequestStatusLog::create([
            'purchase_request_id' => $purchaseRequest->id,
            'old_status' => $oldStatus,
            'new_status' => 'approved',
            'user_id' => auth()->id(),
        ]);
        // Уведомление инициатору
        $purchaseRequest->requester->notify(new SystemEventNotification(
            'Заявка утверждена',
            'Ваша заявка на закупку была утверждена.',
            route('purchase-requests.show', $purchaseRequest->id)
        ));
        return redirect()->route('purchase-requests.index')
            ->with('success', 'Заявка на закупку утверждена.');
    }

    public function reject(Request $request, PurchaseRequest $purchaseRequest)
    {
        $this->authorize('approve', $purchaseRequest);

        $validated = $request->validate([
            'rejection_reason' => 'required',
        ]);
        $oldStatus = $purchaseRequest->status;
        $purchaseRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);
        PurchaseRequestStatusLog::create([
            'purchase_request_id' => $purchaseRequest->id,
            'old_status' => $oldStatus,
            'new_status' => 'rejected',
            'user_id' => auth()->id(),
        ]);
        // Уведомление инициатору
        $purchaseRequest->requester->notify(new SystemEventNotification(
            'Заявка отклонена',
            'Ваша заявка на закупку была отклонена. Причина: ' . $validated['rejection_reason'],
            route('purchase-requests.show', $purchaseRequest->id)
        ));
        return redirect()->route('purchase-requests.index')
            ->with('success', 'Заявка на закупку отклонена.');
    }

    public function archive(PurchaseRequest $purchaseRequest)
    {
        $oldStatus = $purchaseRequest->status;
        $purchaseRequest->archive();
        PurchaseRequestStatusLog::create([
            'purchase_request_id' => $purchaseRequest->id,
            'old_status' => $oldStatus,
            'new_status' => 'archived',
            'user_id' => auth()->id(),
        ]);
        // Уведомление инициатору
        $purchaseRequest->requester->notify(new SystemEventNotification(
            'Заявка архивирована',
            'Ваша заявка на закупку была архивирована.',
            route('purchase-requests.show', $purchaseRequest->id)
        ));
        return redirect()->back()->with('success', 'Заявка успешно архивирована');
    }

    public function unarchive(PurchaseRequest $purchaseRequest)
    {
        $oldStatus = $purchaseRequest->status;
        $purchaseRequest->unarchive();
        PurchaseRequestStatusLog::create([
            'purchase_request_id' => $purchaseRequest->id,
            'old_status' => $oldStatus,
            'new_status' => 'unarchived',
            'user_id' => auth()->id(),
        ]);
        // Уведомление инициатору
        $purchaseRequest->requester->notify(new SystemEventNotification(
            'Заявка восстановлена',
            'Ваша заявка на закупку была восстановлена из архива.',
            route('purchase-requests.show', $purchaseRequest->id)
        ));
        return redirect()->back()->with('success', 'Заявка успешно разархивирована');
    }

    public function archived()
    {
        // Добавляем явную проверку роли
        if (!auth()->user() || !auth()->user()->hasRole('Employee')) {
            abort(404); // Возвращаем 404, если у пользователя нет роли Employee
        }

        $requests = PurchaseRequest::with(['material', 'requester'])
            ->archived()
            ->latest()
            ->paginate(10);
        return view('purchase-requests.archived', compact('requests'));
    }
}

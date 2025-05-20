<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\Supplier;
use Illuminate\Support\Facades\Storage;
use App\Notifications\SystemEventNotification;
use App\Models\User;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contracts = Contract::with('supplier')->latest()->paginate(15);
        return view('contracts.index', compact('contracts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Supplier::all();
        return view('contracts.create', compact('suppliers'));
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
            'number' => 'required',
            'date_start' => 'required|date',
            'date_end' => 'nullable|date|after_or_equal:date_start',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:active,closed,cancelled',
            'file_path' => 'nullable|file|mimes:pdf|max:10240',
        ]);
        if ($request->hasFile('file_path')) {
            $validated['file_path'] = $request->file('file_path')->store('contracts', 'public');
        }
        $contract = Contract::create($validated);
        // Уведомление админам и менеджерам
        foreach (User::whereIn('role', ['admin', 'manager'])->get() as $user) {
            $user->notify(new SystemEventNotification(
                'Добавлен контракт',
                'Добавлен новый контракт №' . $contract->number . ' для поставщика ' . $contract->supplier->name,
                route('contracts.show', $contract->id)
            ));
        }
        return redirect()->route('contracts.show', $contract->id)
            ->with('success', 'Контракт успешно создан.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contract = Contract::with('supplier')->findOrFail($id);
        return view('contracts.show', compact('contract'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contract = Contract::findOrFail($id);
        $suppliers = Supplier::all();
        return view('contracts.edit', compact('contract', 'suppliers'));
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
        $contract = Contract::findOrFail($id);
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'number' => 'required',
            'date_start' => 'required|date',
            'date_end' => 'nullable|date|after_or_equal:date_start',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:active,closed,cancelled',
            'file_path' => 'nullable|file|mimes:pdf|max:10240',
        ]);
        if ($request->hasFile('file_path')) {
            if ($contract->file_path) {
                Storage::disk('public')->delete($contract->file_path);
            }
            $validated['file_path'] = $request->file('file_path')->store('contracts', 'public');
        }
        $contract->update($validated);
        // Уведомление админам и менеджерам
        foreach (User::whereIn('role', ['admin', 'manager'])->get() as $user) {
            $user->notify(new SystemEventNotification(
                'Контракт обновлён',
                'Контракт №' . $contract->number . ' для поставщика ' . $contract->supplier->name . ' был обновлён.',
                route('contracts.show', $contract->id)
            ));
        }
        return redirect()->route('contracts.show', $contract->id)
            ->with('success', 'Контракт успешно обновлён.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contract = Contract::findOrFail($id);
        $number = $contract->number;
        $supplierName = $contract->supplier->name;
        if ($contract->file_path) {
            Storage::disk('public')->delete($contract->file_path);
        }
        $contract->delete();
        // Уведомление админам и менеджерам
        foreach (User::whereIn('role', ['admin', 'manager'])->get() as $user) {
            $user->notify(new SystemEventNotification(
                'Контракт удалён',
                'Контракт №' . $number . ' для поставщика ' . $supplierName . ' был удалён.',
                null
            ));
        }
        return redirect()->route('contracts.index')
            ->with('success', 'Контракт успешно удалён.');
    }
}

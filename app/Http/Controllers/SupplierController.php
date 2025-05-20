<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Notifications\SystemEventNotification;
use App\Models\User;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('suppliers.create');
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
            'inn' => 'required|unique:suppliers',
            'name' => 'required',
            'contact_person' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'additional_info' => 'nullable',
        ]);

        $supplier = Supplier::create($validated);
        // Уведомление админам и менеджерам
        foreach (User::whereIn('role', ['admin', 'manager'])->get() as $user) {
            $user->notify(new SystemEventNotification(
                'Добавлен поставщик',
                'Добавлен новый поставщик: ' . $supplier->name,
                route('suppliers.show', $supplier->id)
            ));
        }
        return redirect()->route('suppliers.index')
            ->with('success', 'Поставщик успешно создан.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $supplier = \App\Models\Supplier::with(['contracts', 'orders'])->findOrFail($id);
        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'inn' => 'required|unique:suppliers,inn,' . $supplier->id,
            'name' => 'required',
            'contact_person' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'additional_info' => 'nullable',
        ]);

        $supplier->update($validated);
        // Уведомление админам и менеджерам
        foreach (User::whereIn('role', ['admin', 'manager'])->get() as $user) {
            $user->notify(new SystemEventNotification(
                'Поставщик обновлён',
                'Поставщик ' . $supplier->name . ' был обновлён.',
                route('suppliers.show', $supplier->id)
            ));
        }
        return redirect()->route('suppliers.index')
            ->with('success', 'Поставщик успешно обновлен.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        $name = $supplier->name;
        $supplier->delete();
        // Уведомление админам и менеджерам
        foreach (User::whereIn('role', ['admin', 'manager'])->get() as $user) {
            $user->notify(new SystemEventNotification(
                'Поставщик удалён',
                'Поставщик ' . $name . ' был удалён.',
                null
            ));
        }
        return redirect()->route('suppliers.index')
            ->with('success', 'Поставщик успешно удален.');
    }
}

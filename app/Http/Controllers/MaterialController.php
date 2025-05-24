<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use App\Models\MaterialDistribution;
use App\Models\Department;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $materials = Material::latest()->paginate(10);
        return view('materials.index', compact('materials'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('materials.create');
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
            'code' => 'required|unique:materials',
            'name' => 'required',
            'description' => 'nullable',
            'unit_of_measure' => 'required',
            'min_quantity' => 'required|numeric|min:0',
            'current_quantity' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        Material::create($validated);

        return redirect()->route('materials.index')
            ->with('success', 'Материал успешно создан.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $material = Material::with(['departments' => function($query) {
            $query->with(['distributions' => function($query) {
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
            }]);
        }])->findOrFail($id);

        $departments = Department::all();
        return view('materials.show', compact('material', 'departments'));
    }

    public function distribute(Request $request, Department $department)
    {
        $validated = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Находим материал по ID, полученному из формы
        $material = \App\Models\Material::findOrFail($validated['material_id']);

        if ($material->current_quantity < $validated['quantity']) {
            return back()->with('distribution_error', 'Недостаточно материалов на складе.');
        }

        // Создаем запись о распределении
        MaterialDistribution::create([
            'material_id' => $material->id,
            'department_id' => $department->id, // Используем $department из маршрута
            'quantity' => $validated['quantity'],
            'distributed_by' => auth()->id(),
        ]);

        // Вычитаем количество со склада
        $material->current_quantity -= $validated['quantity'];
        $material->save();

        // Check material quantity after distribution and send notification if in warning range
        if ($material->current_quantity >= 30 && $material->current_quantity <= 60) {
            // Find users with 'manager' role (adjust roles as needed)
            $usersToNotify = \App\Models\User::where('role', 'Manager')->get();

            foreach ($usersToNotify as $user) {
                // Using the existing MonthlyNormExceededNotification, but the message will indicate low stock
                // You might consider creating a new notification class for low stock specifically if needed.
                $user->notify(new \App\Notifications\MonthlyNormExceededNotification($material, null, $material->current_quantity)); // Pass quantity instead of percentage
            }
        }

        // Проверка месячной нормы после распределения
        $department = Department::with(['materials' => function($query) use ($material) {
            $query->where('materials.id', $material->id);
        }, 'distributions' => function($query) use ($material) {
            $query->where('material_id', $material->id)
                  ->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        }])->findOrFail($department->id);

        $monthlyNorm = $department->materials->first()->pivot->monthly_quantity ?? 0;
        $distributedQuantity = $department->distributions->sum('quantity');
        $percentage = $monthlyNorm > 0 ? round(($distributedQuantity / $monthlyNorm) * 100) : 0;

        // Отправка уведомления при достижении или превышении 80% нормы
        if ($monthlyNorm > 0 && $percentage >= 80) {
            $usersToNotify = \App\Models\User::where('role', 'Admin')
                                                ->orWhere('role', 'Manager')
                                                ->get();

            foreach ($usersToNotify as $user) {
                $user->notify(new \App\Notifications\MonthlyNormExceededNotification($material, $department, $percentage));
            }
        }

        return back()->with('distribution_success', 'Материал успешно выдан.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Material $material)
    {
        return view('materials.edit', compact('material'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'code' => 'required|unique:materials,code,' . $material->id,
            'name' => 'required',
            'description' => 'nullable',
            'unit_of_measure' => 'required',
            'min_quantity' => 'required|numeric|min:0',
            'current_quantity' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $material->update($validated);

        return redirect()->route('materials.index')
            ->with('success', 'Материал успешно обновлен.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Material $material)
    {
        $material->delete();

        return redirect()->route('materials.index')
            ->with('success', 'Материал успешно удален.');
    }
}

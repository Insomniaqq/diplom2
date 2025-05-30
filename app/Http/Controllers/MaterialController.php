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
    public function index(Request $request)
    {
        $query = Material::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->has('code')) {
            $query->where('code', 'like', '%' . $request->input('code') . '%');
        }

        $materials = $query->latest()->paginate(6)->appends($request->query());

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
            'code' => [
                'required',
                'string',
                'max:50',
                'unique:materials',
                'regex:/^[A-Za-z0-9\-_]+$/' // Только буквы, цифры, дефис и подчеркивание
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                'min:3'
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'unit_of_measure' => [
                'required',
                'string',
                'max:20',
                'in:шт,кг,м,л,м²,м³' // Список допустимых единиц измерения
            ],
            'min_quantity' => [
                'required',
                'numeric',
                'min:0',
                'max:999999.99',
                'regex:/^\d+(\.\d{1,2})?$/' // Максимум 2 знака после запятой
            ],
            'current_quantity' => [
                'required',
                'numeric',
                'min:0',
                'max:999999.99',
                'regex:/^\d+(\.\d{1,2})?$/' // Максимум 2 знака после запятой
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
                'max:999999.99',
                'regex:/^\d+(\.\d{1,2})?$/' // Максимум 2 знака после запятой
            ],
        ], [
            'code.required' => 'Код материала обязателен для заполнения',
            'code.unique' => 'Материал с таким кодом уже существует',
            'code.regex' => 'Код может содержать только буквы, цифры, дефис и подчеркивание',
            'name.required' => 'Наименование материала обязательно для заполнения',
            'name.min' => 'Наименование должно содержать минимум 3 символа',
            'unit_of_measure.required' => 'Единица измерения обязательна для заполнения',
            'unit_of_measure.in' => 'Выбрана недопустимая единица измерения',
            'min_quantity.required' => 'Минимальное количество обязательно для заполнения',
            'min_quantity.numeric' => 'Минимальное количество должно быть числом',
            'min_quantity.min' => 'Минимальное количество не может быть отрицательным',
            'current_quantity.required' => 'Текущее количество обязательно для заполнения',
            'current_quantity.numeric' => 'Текущее количество должно быть числом',
            'current_quantity.min' => 'Текущее количество не может быть отрицательным',
            'price.required' => 'Цена обязательна для заполнения',
            'price.numeric' => 'Цена должна быть числом',
            'price.min' => 'Цена не может быть отрицательной',
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
            'material_id' => [
                'required',
                'exists:materials,id'
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:999999',
                function ($attribute, $value, $fail) use ($request) {
                    $material = Material::find($request->material_id);
                    if ($material && $value > $material->current_quantity) {
                        $fail('Запрашиваемое количество превышает доступное на складе.');
                    }
                }
            ],
        ], [
            'material_id.required' => 'Необходимо выбрать материал',
            'material_id.exists' => 'Выбранный материал не существует',
            'quantity.required' => 'Количество обязательно для заполнения',
            'quantity.integer' => 'Количество должно быть целым числом',
            'quantity.min' => 'Количество должно быть не менее 1',
            'quantity.max' => 'Количество не может превышать 999999',
        ]);

        // Находим материал по ID, полученному из формы
        $material = Material::findOrFail($validated['material_id']);

        // Создаем запись о распределении
        MaterialDistribution::create([
            'material_id' => $material->id,
            'department_id' => $department->id,
            'quantity' => $validated['quantity'],
            'distributed_by' => auth()->id(),
        ]);

        // Вычитаем количество со склада
        $material->current_quantity -= $validated['quantity'];
        $material->save();

        // Check material quantity after distribution and send notification if in low stock or zero
        if ($material->current_quantity > 0 && $material->current_quantity <= 50) {
            // Find all users to notify
            $usersToNotify = \App\Models\User::all();

            foreach ($usersToNotify as $user) {
                $user->notify(new \App\Notifications\LowMaterialStockNotification($material, $material->current_quantity));
            }
        } elseif ($material->current_quantity <= 0) {
             // Find all users to notify for zero stock
            $usersToNotify = \App\Models\User::all();

            foreach ($usersToNotify as $user) {
                $user->notify(new \App\Notifications\LowMaterialStockNotification($material, 0));
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
            'code' => [
                'required',
                'string',
                'max:50',
                'unique:materials,code,' . $material->id,
                'regex:/^[A-Za-z0-9\-_]+$/'
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                'min:3'
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'unit_of_measure' => [
                'required',
                'string',
                'max:20',
                'in:шт,кг,м,л,м²,м³'
            ],
            'min_quantity' => [
                'required',
                'numeric',
                'min:0',
                'max:999999.99',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'current_quantity' => [
                'required',
                'numeric',
                'min:0',
                'max:999999.99',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
                'max:999999.99',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
        ], [
            'code.required' => 'Код материала обязателен для заполнения',
            'code.unique' => 'Материал с таким кодом уже существует',
            'code.regex' => 'Код может содержать только буквы, цифры, дефис и подчеркивание',
            'name.required' => 'Наименование материала обязательно для заполнения',
            'name.min' => 'Наименование должно содержать минимум 3 символа',
            'unit_of_measure.required' => 'Единица измерения обязательна для заполнения',
            'unit_of_measure.in' => 'Выбрана недопустимая единица измерения',
            'min_quantity.required' => 'Минимальное количество обязательно для заполнения',
            'min_quantity.numeric' => 'Минимальное количество должно быть числом',
            'min_quantity.min' => 'Минимальное количество не может быть отрицательным',
            'current_quantity.required' => 'Текущее количество обязательно для заполнения',
            'current_quantity.numeric' => 'Текущее количество должно быть числом',
            'current_quantity.min' => 'Текущее количество не может быть отрицательным',
            'price.required' => 'Цена обязательна для заполнения',
            'price.numeric' => 'Цена должна быть числом',
            'price.min' => 'Цена не может быть отрицательной',
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Material;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::all();
        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('departments.create');
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')->with('success', 'Раздел успешно создан.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $department = Department::with(['distributions.material', 'distributions.distributor'])->findOrFail($id);
        return view('departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $department = Department::findOrFail($id);
        $materials = Material::all();
        return view('departments.edit', compact('department', 'materials'));
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
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'materials' => 'nullable|array',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.monthly_quantity' => 'required|integer|min:0',
        ]);

        $department->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        $materialsToSync = [];
        if (isset($validated['materials'])) {
            foreach ($validated['materials'] as $materialData) {
                $materialsToSync[$materialData['material_id']] = ['monthly_quantity' => $materialData['monthly_quantity']];
            }
        }

        $department->materials()->sync($materialsToSync);

        return redirect()->route('departments.index')->with('success', 'Раздел успешно обновлен.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Раздел успешно удален.');
    }

    /**
     * Display monthly norms statistics for the department.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function norms($id)
    {
        $department = Department::with(['materials', 'distributions' => function($query) {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        }])->findOrFail($id);

        $norms = [];
        foreach ($department->materials as $material) {
            $monthlyQuantity = $material->pivot->monthly_quantity;
            $distributedQuantity = $department->distributions
                ->where('material_id', $material->id)
                ->sum('quantity');
            
            $norms[] = [
                'material' => $material,
                'monthly_norm' => $monthlyQuantity,
                'distributed' => $distributedQuantity,
                'remaining' => max(0, $monthlyQuantity - $distributedQuantity),
                'percentage' => $monthlyQuantity > 0 ? 
                    min(100, round(($distributedQuantity / $monthlyQuantity) * 100)) : 0
            ];
        }

        return view('departments.norms', compact('department', 'norms'));
    }
}

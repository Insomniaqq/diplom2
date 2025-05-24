<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $userCounts = [];
        foreach ($roles as $role) {
            $userCounts[$role->id] = \App\Models\User::where('role', $role->name)->count();
        }
        return view('roles.index', [
            'roles' => $roles,
            'userCounts' => $userCounts,
        ]);
    }

    public function create()
    {
        return view('roles.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string|max:1000',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('roles.index')
            ->with('success', __('roles.messages.role_created'));
    }

    public function edit(Role $role)
    {
        return view('roles.form', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $role->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('roles.index')
            ->with('success', __('roles.messages.role_updated'));
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'Нельзя удалить роль, к которой привязаны пользователи.');
        }
        $role->delete();
        return redirect()->route('roles.index')
            ->with('success', 'Роль успешно удалена.');
    }
} 
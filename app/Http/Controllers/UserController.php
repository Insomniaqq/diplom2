<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\UserExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use Illuminate\Notifications\Notification;
use App\Notifications\UserPasswordChangedNotification;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::query();
        $roles = ['Admin', 'Manager', 'Employee'];
        $filters = [
            'role' => $request->input('role'),
            'email' => $request->input('email'),
            'search' => $request->input('search'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
        ];
        if ($filters['role']) {
            $query->where('role', $filters['role']);
        }
        if ($filters['email']) {
            $query->where('email', 'like', '%'.$filters['email'].'%');
        }
        if ($filters['search']) {
            $query->where(function($q) use ($filters) {
                $q->where('name', 'like', '%'.$filters['search'].'%')
                  ->orWhere('email', 'like', '%'.$filters['search'].'%');
            });
        }
        if ($filters['date_from']) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if ($filters['date_to']) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
        $users = $query->orderByDesc('created_at')->paginate(20)->appends($request->all());
        return view('users.index', compact('users', 'roles', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = ['Admin', 'Manager', 'Employee'];
        return view('users.create', compact('roles'));
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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:Admin,Manager,Employee',
        ]);
        $validated['password'] = bcrypt($validated['password']);
        $user = \App\Models\User::create($validated);
        // Отправка уведомления пользователю
        $user->notify(new \App\Notifications\UserCreatedNotification($user));
        return redirect()->route('users.index')->with('success', 'Пользователь успешно создан.');
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

    public function export(Request $request)
    {
        $filters = [
            'role' => $request->input('role'),
            'email' => $request->input('email'),
            'search' => $request->input('search'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
        ];
        return Excel::download(new UserExport($filters), 'users.xlsx');
    }

    // Форма смены пароля (только для админа)
    public function changePasswordForm($id)
    {
        $user = User::findOrFail($id);
        abort_unless(auth()->user()->hasRole('admin'), 403);
        return view('users.change-password', compact('user'));
    }

    // Обработка смены пароля
    public function changePassword(Request $request, $id)
    {
        abort_unless(auth()->user()->hasRole('admin'), 403);
        $user = User::findOrFail($id);
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user->password = bcrypt($request->password);
        $user->save();
        $user->notify(new UserPasswordChangedNotification($user));
        return redirect()->route('users.index')->with('success', 'Пароль успешно изменён.');
    }
}

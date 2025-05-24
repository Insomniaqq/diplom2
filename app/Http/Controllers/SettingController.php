<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    public function index()
    {
        $threshold = config('settings.monthly_norm_notification_threshold');
        return view('settings.index', compact('threshold'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'monthly_norm_notification_threshold' => 'required|integer|min:0|max:100',
        ]);

        // Сохранение настройки в файле .env (или другом месте, которое читает config())
        // Для простоты, сохраним в .env. В реальном приложении можно использовать базу данных.
        $path = base_path('.env');
        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                'MONTHLY_NORM_NOTIFICATION_THRESHOLD=' . env('MONTHLY_NORM_NOTIFICATION_THRESHOLD'),
                'MONTHLY_NORM_NOTIFICATION_THRESHOLD=' . $validated['monthly_norm_notification_threshold'],
                file_get_contents($path)
            ));
        }

        // Очистка кэша конфигурации, чтобы новое значение вступило в силу
        Artisan::call('config:clear');

        return redirect()->route('settings.index')->with('success', 'Настройки успешно обновлены.');
    }
}

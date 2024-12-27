<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class DealSettingsController extends Controller
{
    public function index()
    {
        $commissionSettings = DB::table('commission_settings')->get();
        $timerSettings = DB::table('timer_settings')->get();
        $categories = Category::all(); // Получаем все категории

        return view('admin.deal_settings.index', compact('commissionSettings', 'timerSettings', 'categories'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'default_commission' => 'required|numeric|min:0|max:100',
            'categories' => 'array',
            'categories.*' => 'numeric|min:0|max:100',
            'timer' => 'required|integer|min:0',
        ]);

        // Обновляем настройку комиссии по умолчанию
        DB::table('commission_settings')->updateOrInsert(
            ['name' => 'Default Commission'],
            ['value' => $request->input('default_commission')]
        );

        // Обновляем настройки для каждой категории
        if ($request->has('categories')) {
            foreach ($request->input('categories') as $categoryId => $commission) {
                DB::table('categories')->where('id', $categoryId)->update(['commission' => $commission]);
            }
        }

        // Обновляем настройку таймера
        DB::table('timer_settings')->updateOrInsert(
            ['name' => 'Default Timer'],
            ['value' => $request->input('timer')]
        );

        return redirect()->back()->with('success', 'Настройки успешно обновлены.');
    }

}

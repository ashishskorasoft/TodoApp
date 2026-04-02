<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AuthorizesPermissions;
use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    use AuthorizesPermissions;

    public function edit(Request $request): View
    {
        $this->requirePermission($request, 'settings.view');

        $settings = [
            'app_name' => AppSetting::getValue('app_name', 'TodoFlow'),
            'support_email' => AppSetting::getValue('support_email', ''),
            'theme_color' => AppSetting::getValue('theme_color', '#7e57c2'),
            'daily_summary_time' => AppSetting::getValue('daily_summary_time', '08:00 AM'),
        ];

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $this->requirePermission($request, 'settings.update');

        $validated = $request->validate([
            'app_name' => ['required', 'string', 'max:80'],
            'support_email' => ['nullable', 'email', 'max:255'],
            'theme_color' => ['required', 'string', 'max:20'],
            'daily_summary_time' => ['required', 'string', 'max:20'],
        ]);

        foreach ($validated as $key => $value) {
            AppSetting::putValue($key, $value);
        }

        return redirect()->route('admin.settings.edit')->with('success', 'Settings saved successfully.');
    }
}

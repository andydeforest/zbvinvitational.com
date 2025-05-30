<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SettingKey;
use App\Http\Controllers\Controller;
use App\Services\Settings;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'event_date' => ['nullable', 'date'],
            'event_location_name' => ['nullable', 'string', 'max:255'],
            'event_location_address' => ['nullable', 'string', 'max:500'],
        ]);

        if (! is_null($data['event_date'])) {
            // normalize into ISO-8601
            $iso = Carbon::parse($data['event_date'])
                ->setTimezone(config('app.timezone'))
                ->toIso8601String();
            Settings::set(SettingKey::EventDate, $iso);
        }

        Settings::set(SettingKey::EventLocationName, $data['event_location_name']);
        Settings::set(SettingKey::EventLocationAddress, $data['event_location_address']);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Settings saved successfully.');
    }
}

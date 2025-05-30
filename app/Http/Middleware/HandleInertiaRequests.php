<?php

namespace App\Http\Middleware;

use App\Enums\SettingKey;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'event_date' => function () {
                $raw = \Settings::get(SettingKey::EventDate);
                if (! $raw) {
                    return null;
                }

                return Carbon::parse($raw)
                    ->timezone(config('app.timezone'))
                    ->format(\DateTime::ATOM);
            },
            'event_location_name' => \Settings::get(SettingKey::EventLocationName, ''),
            'event_location_address' => \Settings::get(SettingKey::EventLocationAddress, ''),
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
            ],
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Donor\StoreDonorRequest;
use App\Http\Requests\Admin\Donor\UpdateDonorRequest;
use App\Http\Resources\DonorLogoResource;
use App\Models\Assets\DonorLogo;
use App\Models\Donor;
use Inertia\Inertia;

class DonorController extends Controller
{
    public function index()
    {
        $logos = DonorLogo::with('media')
            ->get();

        return Inertia::render('Admin/Donors/Index', [
            'donors' => Donor::orderBy('name')->get(),
            'logos' => DonorLogoResource::collection($logos),
        ]);
    }

    public function store(StoreDonorRequest $request)
    {
        $validated = $request->validated();

        /** @var array<array{name: string, id?: int}> $donors */
        $donors = $validated['donors'];

        /** @var array<array{name: string, id?: int}> $donors */
        foreach ($donors as $donor) {
            Donor::create($donor);
        }

        return redirect()
            ->route('admin.donors.index')
            ->with('success', 'Donors created.');
    }

    public function update(UpdateDonorRequest $request)
    {
        /**
         * @var array{
         *   donors: array<int, array{name: string, id?: int}>,
         *   deleted?: array<int>
         * } $data
         */
        $data = $request->validated();

        /** @var array<int, array{name: string, id?: int}> $donors */
        $donors = $data['donors'];

        /** @var int[] $deleted */
        $deleted = $data['deleted'] ?? [];

        if (! empty($deleted)) {
            Donor::whereIn('id', $deleted)->delete();
        }

        foreach ($donors as $entry) {
            /** @var array{name: string, id?: int} $entry */
            if (isset($entry['id'])) {
                Donor::where('id', $entry['id'])
                    ->update(['name' => $entry['name']]);
            } else {
                Donor::create(['name' => $entry['name']]);
            }
        }

        return redirect()
            ->route('admin.donors.index')
            ->with('success', 'Donors updated.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ServiceAddonController extends Controller
{
    public function edit(Service $service): View
    {
        abort_unless($service->user_id === auth()->id(), 403);

        return view('services.addons', [
            'service' => $service,
            'addons' => $service->addons()->get(),
        ]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        abort_unless($service->user_id === auth()->id(), 403);

        $validated = $request->validate([
            'return_to_settings' => ['nullable', 'boolean'],
            'addons' => ['nullable', 'array', 'max:20'],
            'addons.*.id' => ['nullable', 'integer'],
            'addons.*.name' => ['required', 'string', 'max:100'],
            'addons.*.description' => ['nullable', 'string', 'max:255'],
            'addons.*.price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'addons.*.is_active' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($service, $validated) {
            $existing = $service->addons()->lockForUpdate()->get()->keyBy('id');
            $keptAddonIds = [];

            foreach (array_values($validated['addons'] ?? []) as $position => $addon) {
                $payload = [
                    'name' => trim($addon['name']),
                    'description' => filled($addon['description'] ?? null) ? trim($addon['description']) : null,
                    'price' => $addon['price'],
                    'is_active' => (bool) ($addon['is_active'] ?? false),
                    'sort_order' => $position,
                ];

                if (! empty($addon['id'])) {
                    $model = $existing->get((int) $addon['id']);
                    abort_unless($model, 403);
                    $model->update($payload);
                    $keptAddonIds[] = $model->id;
                    continue;
                }

                $keptAddonIds[] = $service->addons()->create($payload)->id;
            }

            $service->addons()->whereNotIn('id', $keptAddonIds)->delete();
        });

        $redirectTo = $request->boolean('return_to_settings')
            ? route('services.edit', ['id' => $service->id, 'tab' => 'addons'])
            : route('services.addons.edit', $service);

        return redirect()
            ->to($redirectTo)
            ->with('success', 'Layanan tambahan berhasil disimpan.');
    }
}

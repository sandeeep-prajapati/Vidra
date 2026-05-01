<?php

namespace App\Packages\Webhook\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\Webhook\Repositories\SettingsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookSettingsController extends Controller
{
    public function __construct(protected SettingsRepository $settingsRepository) {}

    public function index()
    {
        $settings = $this->settingsRepository->all();

        return view('webhook::settings.index', compact('settings'));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'webhook_url' => 'nullable|url',
        ]);

        $fields = [
            'webhook_active' => (int) $request->boolean('webhook_active'),
            'webhook_url'    => $request->input('webhook_url'),
        ];

        foreach ($fields as $field => $value) {
            $this->settingsRepository->createOrUpdate($field, $value);
        }

        return response()->json([
            'success' => true,
            'message' => __('webhook::app.settings.saved'),
        ]);
    }

    public function show(): JsonResponse
    {
        return response()->json([
            'data' => $this->settingsRepository->all(),
        ]);
    }
}

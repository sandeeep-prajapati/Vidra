<?php

namespace App\Packages\Webhook\Services;

use App\Packages\Webhook\Repositories\LogsRepository;
use App\Packages\Webhook\Repositories\SettingsRepository;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class WebhookService
{
    public function __construct(
        protected SettingsRepository $settingsRepository,
        protected LogsRepository $logsRepository,
    ) {}

    /**
     * Queue a webhook dispatch as a background job.
     * This is the primary entry point called from controllers/listeners.
     */
    public function dispatch(string $event, string $entityType, int $entityId, array $data, ?string $triggeredBy = null): void
    {
        if (! $this->settingsRepository->isActive()) {
            return;
        }

        \App\Packages\Webhook\Jobs\SendWebhook::dispatch(
            $event,
            $entityType,
            $entityId,
            $data,
            $triggeredBy ?? (auth()->user()?->name ?? 'system'),
        );
    }

    /**
     * Synchronous HTTP send — called by the SendWebhook job.
     */
    public function dispatchSync(string $event, string $entityType, int $entityId, array $data, ?string $triggeredBy = null): ?Response
    {
        $url = $this->settingsRepository->getUrl();

        if (empty($url)) {
            return null;
        }

        $payload = [
            'event'       => $event,
            'entity_type' => $entityType,
            'entity_id'   => $entityId,
            'timestamp'   => now()->toIso8601String(),
            'data'        => $data,
        ];

        $response = null;
        $success  = false;

        try {
            $response = Http::timeout(10)->post($url, $payload);
            $success  = $response->successful();
        } catch (\Exception $e) {
            report($e);
        }

        $this->logsRepository->create([
            'event'        => $event,
            'entity_type'  => $entityType,
            'entity_id'    => $entityId,
            'triggered_by' => $triggeredBy ?? 'system',
            'status'       => $success,
            'payload'      => $payload,
            'response'     => $response
                ? ['status' => $response->status(), 'body' => $response->body()]
                : null,
        ]);

        return $response;
    }
}

<?php

namespace App\Packages\Webhook\Jobs;

use App\Packages\Webhook\Services\WebhookService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 10;

    public function __construct(
        public readonly string $event,
        public readonly string $entityType,
        public readonly int $entityId,
        public readonly array $data,
        public readonly ?string $triggeredBy = null,
    ) {}

    public function handle(WebhookService $webhookService): void
    {
        $webhookService->dispatchSync($this->event, $this->entityType, $this->entityId, $this->data, $this->triggeredBy);
    }
}

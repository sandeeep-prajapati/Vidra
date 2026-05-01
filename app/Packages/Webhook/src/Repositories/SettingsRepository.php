<?php

namespace App\Packages\Webhook\Repositories;

use App\Packages\Webhook\Models\WebhookSetting;

class SettingsRepository
{
    public function __construct(protected WebhookSetting $model) {}

    public function createOrUpdate(string $field, mixed $value, array $extra = []): WebhookSetting
    {
        return $this->model->updateOrCreate(
            ['field' => $field],
            ['value' => $value, 'extra' => $extra ?: null]
        );
    }

    public function all(): array
    {
        $normalized = [];

        foreach ($this->model->all() as $setting) {
            $normalized[$setting->field] = $setting->value;
        }

        return $normalized;
    }

    public function isActive(): bool
    {
        return (bool) ((int) ($this->model->where('field', 'webhook_active')->first()?->value ?? 0));
    }

    public function getUrl(): ?string
    {
        return $this->model->where('field', 'webhook_url')->first()?->value;
    }

    public function getEvents(): array
    {
        $value = $this->model->where('field', 'webhook_events')->first()?->value;

        return $value ? json_decode($value, true) : [];
    }
}

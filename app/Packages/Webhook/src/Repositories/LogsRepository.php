<?php

namespace App\Packages\Webhook\Repositories;

use App\Packages\Webhook\Models\WebhookLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LogsRepository
{
    public function __construct(protected WebhookLog $model) {}

    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return $this->model->latest()->paginate($perPage);
    }

    public function create(array $data): WebhookLog
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?WebhookLog
    {
        return $this->model->find($id);
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->destroy($id);
    }

    public function deleteMany(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }
}

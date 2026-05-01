<?php

namespace App\Packages\DataTransfer\Repositories;

use App\Packages\DataTransfer\Models\TransferJob;
use Illuminate\Database\Eloquent\Collection;

class TransferJobRepository
{
    public function __construct(protected TransferJob $model) {}

    public function all(): Collection
    {
        return $this->model->with('latestTrack')->latest()->get();
    }

    public function find(int $id): ?TransferJob
    {
        return $this->model->with('tracks')->find($id);
    }

    public function create(array $data): TransferJob
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): TransferJob
    {
        $job = $this->model->findOrFail($id);
        $job->update($data);

        return $job->fresh();
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->destroy($id);
    }
}

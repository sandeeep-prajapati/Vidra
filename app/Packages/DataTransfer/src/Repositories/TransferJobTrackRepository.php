<?php

namespace App\Packages\DataTransfer\Repositories;

use App\Packages\DataTransfer\Models\TransferJobTrack;
use Illuminate\Database\Eloquent\Collection;

class TransferJobTrackRepository
{
    public function __construct(protected TransferJobTrack $model) {}

    public function forJob(int $jobId): Collection
    {
        return $this->model->where('transfer_job_id', $jobId)->latest()->get();
    }

    public function find(int $id): ?TransferJobTrack
    {
        return $this->model->find($id);
    }

    public function create(array $data): TransferJobTrack
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): TransferJobTrack
    {
        $track = $this->model->findOrFail($id);
        $track->update($data);

        return $track->fresh();
    }
}

<?php

namespace App\Packages\Webhook\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\Webhook\Repositories\LogsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookLogsController extends Controller
{
    public function __construct(protected LogsRepository $logsRepository) {}

    public function index()
    {
        $logs = $this->logsRepository->paginate(25);

        return view('webhook::logs.index', compact('logs'));
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->logsRepository->delete($id);

        if (! $deleted) {
            return response()->json(['message' => __('webhook::app.logs.not_found')], 404);
        }

        return response()->json(['message' => __('webhook::app.logs.deleted')]);
    }

    public function massDestroy(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['message' => __('webhook::app.logs.no_ids')], 422);
        }

        $count = $this->logsRepository->deleteMany($ids);

        return response()->json([
            'message' => __('webhook::app.logs.mass_deleted', ['count' => $count]),
        ]);
    }
}

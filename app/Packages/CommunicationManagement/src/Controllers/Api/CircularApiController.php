<?php

namespace App\Packages\CommunicationManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\CommunicationManagement\Models\Circular;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Communication Management
 */
class CircularApiController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Circular::with('issuer')->orderByDesc('issued_date')->paginate(20)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:150',
            'content'         => 'required|string',
            'issued_by'       => 'nullable|exists:users,id',
            'issued_date'     => 'required|date',
            'target_audience' => 'required|in:All,Students,Parents,Teachers,Staff',
            'attachment_url'  => 'nullable|string|max:255',
        ]);

        $item = Circular::create($validated);

        return response()->json($item->load('issuer'), 201);
    }

    public function show(Circular $circular): JsonResponse
    {
        return response()->json($circular->load('issuer'));
    }

    public function update(Request $request, Circular $circular): JsonResponse
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:150',
            'content'         => 'required|string',
            'issued_by'       => 'nullable|exists:users,id',
            'issued_date'     => 'required|date',
            'target_audience' => 'required|in:All,Students,Parents,Teachers,Staff',
            'attachment_url'  => 'nullable|string|max:255',
        ]);

        $circular->update($validated);

        return response()->json($circular->fresh('issuer'));
    }

    public function destroy(Circular $circular): JsonResponse
    {
        $circular->delete();
        return response()->json(['message' => 'Circular deleted.']);
    }
}

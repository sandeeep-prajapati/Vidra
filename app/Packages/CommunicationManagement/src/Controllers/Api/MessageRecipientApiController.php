<?php

namespace App\Packages\CommunicationManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\CommunicationManagement\Models\MessageRecipient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Communication Management
 */
class MessageRecipientApiController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            MessageRecipient::with('message', 'user')->orderByDesc('created_at')->paginate(20)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message_id'   => 'required|exists:messages,message_id',
            'user_id'      => 'required|exists:users,id',
            'status'       => 'required|in:Pending,Sent,Failed,Read',
            'delivered_at' => 'nullable|date',
            'read_at'      => 'nullable|date',
        ]);

        $item = MessageRecipient::create($validated);

        return response()->json($item->load('message', 'user'), 201);
    }

    public function show(MessageRecipient $messageRecipient): JsonResponse
    {
        return response()->json($messageRecipient->load('message', 'user'));
    }

    public function update(Request $request, MessageRecipient $messageRecipient): JsonResponse
    {
        $validated = $request->validate([
            'message_id'   => 'required|exists:messages,message_id',
            'user_id'      => 'required|exists:users,id',
            'status'       => 'required|in:Pending,Sent,Failed,Read',
            'delivered_at' => 'nullable|date',
            'read_at'      => 'nullable|date',
        ]);

        $messageRecipient->update($validated);

        return response()->json($messageRecipient->fresh('message', 'user'));
    }

    public function destroy(MessageRecipient $messageRecipient): JsonResponse
    {
        $messageRecipient->delete();
        return response()->json(['message' => 'Recipient deleted.']);
    }
}

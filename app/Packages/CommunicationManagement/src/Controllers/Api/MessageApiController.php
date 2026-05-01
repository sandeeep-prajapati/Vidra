<?php

namespace App\Packages\CommunicationManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\CommunicationManagement\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Communication Management
 */
class MessageApiController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Message::with('sender')->orderByDesc('created_at')->paginate(20)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'        => 'nullable|string|max:150',
            'content'      => 'required|string',
            'message_type' => 'required|in:SMS,Email,App Notification,Circular,Announcement',
            'created_by'   => 'nullable|exists:users,id',
            'scheduled_at' => 'nullable|date',
            'priority'     => 'required|in:Low,Normal,High,Urgent',
            'is_sent'      => 'boolean',
        ]);

        $item = Message::create($validated);

        return response()->json($item->load('sender'), 201);
    }

    public function show(Message $message): JsonResponse
    {
        return response()->json($message->load('sender', 'recipients.user'));
    }

    public function update(Request $request, Message $message): JsonResponse
    {
        $validated = $request->validate([
            'title'        => 'nullable|string|max:150',
            'content'      => 'required|string',
            'message_type' => 'required|in:SMS,Email,App Notification,Circular,Announcement',
            'created_by'   => 'nullable|exists:users,id',
            'scheduled_at' => 'nullable|date',
            'priority'     => 'required|in:Low,Normal,High,Urgent',
            'is_sent'      => 'boolean',
        ]);

        $message->update($validated);

        return response()->json($message->fresh('sender'));
    }

    public function destroy(Message $message): JsonResponse
    {
        $message->delete();
        return response()->json(['message' => 'Message deleted.']);
    }
}

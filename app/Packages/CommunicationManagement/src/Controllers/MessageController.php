<?php

namespace App\Packages\CommunicationManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Packages\CommunicationManagement\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $query = Message::with('sender');

        if ($request->filled('message_type')) {
            $query->where('message_type', $request->message_type);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('is_sent')) {
            $query->where('is_sent', $request->is_sent);
        }

        $items = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('communication::message.index', compact('items'));
    }

    public function create(): View
    {
        $users = User::orderBy('name')->get();
        return view('communication::message.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
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

        $validated['is_sent'] = $request->boolean('is_sent');

        Message::create($validated);

        return redirect()->route('message.index')->with('success', 'Message created successfully.');
    }

    public function show(Message $message): View
    {
        $message->load('sender', 'recipients.user');
        return view('communication::message.show', compact('message'));
    }

    public function edit(Message $message): View
    {
        $users = User::orderBy('name')->get();
        return view('communication::message.edit', compact('message', 'users'));
    }

    public function update(Request $request, Message $message): RedirectResponse
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

        $validated['is_sent'] = $request->boolean('is_sent');

        $message->update($validated);

        return redirect()->route('message.index')->with('success', 'Message updated successfully.');
    }

    public function destroy(Message $message): RedirectResponse
    {
        $message->delete();
        return redirect()->route('message.index')->with('success', 'Message deleted.');
    }
}

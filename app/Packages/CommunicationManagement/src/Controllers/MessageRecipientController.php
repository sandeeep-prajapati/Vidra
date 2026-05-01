<?php

namespace App\Packages\CommunicationManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Packages\CommunicationManagement\Models\Message;
use App\Packages\CommunicationManagement\Models\MessageRecipient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageRecipientController extends Controller
{
    public function index(Request $request): View
    {
        $query = MessageRecipient::with('message', 'user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('message_id')) {
            $query->where('message_id', $request->message_id);
        }

        $items    = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        $messages = Message::orderByDesc('created_at')->get();

        return view('communication::messageRecipient.index', compact('items', 'messages'));
    }

    public function create(): View
    {
        $messages = Message::orderByDesc('created_at')->get();
        $users    = User::orderBy('name')->get();
        return view('communication::messageRecipient.create', compact('messages', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'message_id'   => 'required|exists:messages,message_id',
            'user_id'      => 'required|exists:users,id',
            'status'       => 'required|in:Pending,Sent,Failed,Read',
            'delivered_at' => 'nullable|date',
            'read_at'      => 'nullable|date',
        ]);

        MessageRecipient::create($validated);

        return redirect()->route('messageRecipient.index')->with('success', 'Recipient added successfully.');
    }

    public function show(MessageRecipient $messageRecipient): View
    {
        $messageRecipient->load('message', 'user');
        return view('communication::messageRecipient.show', compact('messageRecipient'));
    }

    public function edit(MessageRecipient $messageRecipient): View
    {
        $messages = Message::orderByDesc('created_at')->get();
        $users    = User::orderBy('name')->get();
        return view('communication::messageRecipient.edit', compact('messageRecipient', 'messages', 'users'));
    }

    public function update(Request $request, MessageRecipient $messageRecipient): RedirectResponse
    {
        $validated = $request->validate([
            'message_id'   => 'required|exists:messages,message_id',
            'user_id'      => 'required|exists:users,id',
            'status'       => 'required|in:Pending,Sent,Failed,Read',
            'delivered_at' => 'nullable|date',
            'read_at'      => 'nullable|date',
        ]);

        $messageRecipient->update($validated);

        return redirect()->route('messageRecipient.index')->with('success', 'Recipient updated successfully.');
    }

    public function destroy(MessageRecipient $messageRecipient): RedirectResponse
    {
        $messageRecipient->delete();
        return redirect()->route('messageRecipient.index')->with('success', 'Recipient deleted.');
    }
}

<?php

namespace App\Packages\CommunicationManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Packages\CommunicationManagement\Models\NotificationSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationSettingController extends Controller
{
    public function index(Request $request): View
    {
        $query = NotificationSetting::with('user');

        if ($request->filled('search')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }

        $items = $query->orderBy('setting_id')->paginate(20)->withQueryString();

        return view('communication::notificationSetting.index', compact('items'));
    }

    public function create(): View
    {
        $existingUserIds = NotificationSetting::pluck('user_id')->all();
        $users = User::orderBy('name')->whereNotIn('id', $existingUserIds)->get();
        return view('communication::notificationSetting.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id'             => 'required|exists:users,id|unique:notification_settings,user_id',
            'allow_sms'           => 'boolean',
            'allow_email'         => 'boolean',
            'allow_app'           => 'boolean',
            'allow_announcements' => 'boolean',
            'allow_circulars'     => 'boolean',
        ]);

        $validated['allow_sms']           = $request->boolean('allow_sms');
        $validated['allow_email']         = $request->boolean('allow_email');
        $validated['allow_app']           = $request->boolean('allow_app');
        $validated['allow_announcements'] = $request->boolean('allow_announcements');
        $validated['allow_circulars']     = $request->boolean('allow_circulars');

        NotificationSetting::create($validated);

        return redirect()->route('notificationSetting.index')->with('success', 'Notification preferences saved.');
    }

    public function show(NotificationSetting $notificationSetting): View
    {
        $notificationSetting->load('user');
        return view('communication::notificationSetting.show', compact('notificationSetting'));
    }

    public function edit(NotificationSetting $notificationSetting): View
    {
        $notificationSetting->load('user');
        return view('communication::notificationSetting.edit', compact('notificationSetting'));
    }

    public function update(Request $request, NotificationSetting $notificationSetting): RedirectResponse
    {
        $validated = $request->validate([
            'allow_sms'           => 'boolean',
            'allow_email'         => 'boolean',
            'allow_app'           => 'boolean',
            'allow_announcements' => 'boolean',
            'allow_circulars'     => 'boolean',
        ]);

        $validated['allow_sms']           = $request->boolean('allow_sms');
        $validated['allow_email']         = $request->boolean('allow_email');
        $validated['allow_app']           = $request->boolean('allow_app');
        $validated['allow_announcements'] = $request->boolean('allow_announcements');
        $validated['allow_circulars']     = $request->boolean('allow_circulars');

        $notificationSetting->update($validated);

        return redirect()->route('notificationSetting.index')->with('success', 'Notification preferences updated.');
    }

    public function destroy(NotificationSetting $notificationSetting): RedirectResponse
    {
        $notificationSetting->delete();
        return redirect()->route('notificationSetting.index')->with('success', 'Notification settings deleted.');
    }
}

<?php

namespace App\Packages\CommunicationManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\CommunicationManagement\Models\NotificationSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Communication Management
 */
class NotificationSettingApiController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            NotificationSetting::with('user')->orderBy('setting_id')->paginate(20)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id'             => 'required|exists:users,id|unique:notification_settings,user_id',
            'allow_sms'           => 'boolean',
            'allow_email'         => 'boolean',
            'allow_app'           => 'boolean',
            'allow_announcements' => 'boolean',
            'allow_circulars'     => 'boolean',
        ]);

        $item = NotificationSetting::create($validated);

        return response()->json($item->load('user'), 201);
    }

    public function show(NotificationSetting $notificationSetting): JsonResponse
    {
        return response()->json($notificationSetting->load('user'));
    }

    public function update(Request $request, NotificationSetting $notificationSetting): JsonResponse
    {
        $validated = $request->validate([
            'allow_sms'           => 'boolean',
            'allow_email'         => 'boolean',
            'allow_app'           => 'boolean',
            'allow_announcements' => 'boolean',
            'allow_circulars'     => 'boolean',
        ]);

        $notificationSetting->update($validated);

        return response()->json($notificationSetting->fresh('user'));
    }

    public function destroy(NotificationSetting $notificationSetting): JsonResponse
    {
        $notificationSetting->delete();
        return response()->json(['message' => 'Notification settings deleted.']);
    }
}

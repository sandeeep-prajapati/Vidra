<?php

namespace App\Packages\Pro\AlumniManagement\Providers;

use Illuminate\Support\Facades\Auth;

class MenuProvider
{
    public static function getMenuItems(): array
    {
        if (!Auth::check()) {
            return [];
        }

        return [
            [
                'label'   => 'Alumni',
                'section' => 'alumni',
                'items'   => [
                    ['label' => 'Directory',  'route' => 'alumni.directory',        'permission' => 'view_alumni-management'],
                    ['label' => 'Events',     'route' => 'alumni.events.index',     'permission' => 'view_alumni-management'],
                    ['label' => 'Donations',  'route' => 'alumni.donations.index',  'permission' => 'manage_alumni_donations'],
                    ['label' => 'Mentorship', 'route' => 'alumni.mentorship.index', 'permission' => 'manage_alumni_mentorship'],
                ],
            ],
        ];
    }
}

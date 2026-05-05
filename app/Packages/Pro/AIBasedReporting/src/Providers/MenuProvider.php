<?php

namespace App\Packages\Pro\AIBasedReporting\Providers\Providers;

class MenuProvider
{
    public static function getMenuItems(): array
    {
        if (!auth()->check()) {
            return [];
        }

        $items = [];

        // Main menu item
        if (auth()->user()->can('view_a-i-based-reporting')) {
            $items[] = [
                'label' => 'AIBasedReporting',
                'route' => 'a-i-based-reporting.index',
                'icon' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM15 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2zM5 13a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5z"/></svg>',
                'active' => request()->routeIs('a-i-based-reporting.*'),
                'permission' => 'view_a-i-based-reporting',
            ];
        }

        // Admin submenu
        if (auth()->user()->hasRole('admin')) {
            $items[] = ['type' => 'divider'];
            $items[] = [
                'label' => 'AIBasedReporting Admin',
                'submenu' => [
                    [
                        'label' => 'Settings',
                        'route' => 'a-i-based-reporting.settings',
                        'active' => request()->routeIs('a-i-based-reporting.settings'),
                    ],
                    [
                        'label' => 'Export Data',
                        'route' => 'a-i-based-reporting.export',
                        'active' => request()->routeIs('a-i-based-reporting.export'),
                    ],
                ],
            ];
        }

        return $items;
    }
}
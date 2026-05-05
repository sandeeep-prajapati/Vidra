<?php

namespace App\Packages\Pro\TestBundle\Providers\Providers;

class MenuProvider
{
    public static function getMenuItems(): array
    {
        if (!auth()->check()) {
            return [];
        }

        $items = [];

        // Main menu item
        if (auth()->user()->can('view_test-bundle')) {
            $items[] = [
                'label' => 'TestBundle',
                'route' => 'test-bundle.index',
                'icon' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM15 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2zM5 13a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5z"/></svg>',
                'active' => request()->routeIs('test-bundle.*'),
                'permission' => 'view_test-bundle',
            ];
        }

        // Admin submenu
        if (auth()->user()->hasRole('admin')) {
            $items[] = ['type' => 'divider'];
            $items[] = [
                'label' => 'TestBundle Admin',
                'submenu' => [
                    [
                        'label' => 'Settings',
                        'route' => 'test-bundle.settings',
                        'active' => request()->routeIs('test-bundle.settings'),
                    ],
                    [
                        'label' => 'Export Data',
                        'route' => 'test-bundle.export',
                        'active' => request()->routeIs('test-bundle.export'),
                    ],
                ],
            ];
        }

        return $items;
    }
}
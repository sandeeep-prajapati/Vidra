<?php

namespace App\Packages\Pro\LibraryManagement\Providers\Providers;

class MenuProvider
{
    public static function getMenuItems(): array
    {
        if (!auth()->check()) {
            return [];
        }

        $items = [];

        // Main menu item
        if (auth()->user()->can('view_library-management')) {
            $items[] = [
                'label' => 'LibraryManagement',
                'route' => 'library-management.index',
                'icon' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM15 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2zM5 13a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5z"/></svg>',
                'active' => request()->routeIs('library-management.*'),
                'permission' => 'view_library-management',
            ];
        }

        // Admin submenu
        if (auth()->user()->hasRole('admin')) {
            $items[] = ['type' => 'divider'];
            $items[] = [
                'label' => 'LibraryManagement Admin',
                'submenu' => [
                    [
                        'label' => 'Settings',
                        'route' => 'library-management.settings',
                        'active' => request()->routeIs('library-management.settings'),
                    ],
                    [
                        'label' => 'Export Data',
                        'route' => 'library-management.export',
                        'active' => request()->routeIs('library-management.export'),
                    ],
                ],
            ];
        }

        return $items;
    }
}
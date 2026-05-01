<?php

namespace App\Packages\RbacManagement\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $fillable = ['name', 'guard_name', 'description', 'module_name'];
}

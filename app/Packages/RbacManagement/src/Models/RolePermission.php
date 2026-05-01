<?php

namespace App\Packages\RbacManagement\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = 'role_permissions';

    protected $primaryKey = 'role_permission_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        // Define fillable fields
    ];
}

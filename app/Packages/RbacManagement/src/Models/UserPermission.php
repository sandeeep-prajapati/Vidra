<?php

namespace App\Packages\RbacManagement\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    protected $table = 'user_permissions';

    protected $primaryKey = 'user_permission_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        // Define fillable fields
    ];
}

<?php

namespace App\Packages\CorePackage\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the connection name for the model.
     *
     * @var string|null
     */
    protected $connection = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Scope a query to filter by specified columns.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeFilter($query, array $filters)
    {
        foreach ($filters as $key => $value) {
            if (method_exists($this, 'scope'.ucfirst($key))) {
                $query->{'scope'.ucfirst($key)}($value);
            } elseif (in_array($key, $this->fillable) || array_key_exists($key, $this->attributes)) {
                $query->where($key, $value);
            }
        }

        return $query;
    }
}

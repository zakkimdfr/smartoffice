<?php namespace Horizonstack\Userextend\Models;

use Model;

/**
 * Model
 */
class JobLevel extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'horizonstack_userextend_job_levels';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
    ];

    public function scopeIsActive($query)
    {
        return $query->where('is_active', true);
    }
}

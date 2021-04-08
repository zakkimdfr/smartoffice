<?php namespace Horizonstack\Userextend\Models;

use Model;

/**
 * Model
 */
class SubDepartment extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'horizonstack_userextend_sub_departments';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        'department' => 'required',
    ];

    public $belongsTo = ['department' => Department::class];

    public function scopeIsActive($query)
    {
        return $query->where('is_active', true);
    }
}

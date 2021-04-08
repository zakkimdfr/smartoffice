<?php namespace Horizonstack\Workreport\Models;

use Model;

/**
 * Model
 */
class ReportAttn extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'horizonstack_workreport_attns';

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

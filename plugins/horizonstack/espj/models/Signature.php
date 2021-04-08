<?php namespace Horizonstack\eSPJ\Models;

use Model;

/**
 * Model
 */
class Signature extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'horizonstack_espj_signatures';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'            => 'required',
        'identity_number' => 'required',
        'job_position'    => 'required',
    ];

    public function scopeIsActive($query)
    {
        return $query->where('is_active', true);
    }
}

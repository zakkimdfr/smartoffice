<?php namespace Horizonstack\Investment\Models;

use Model;

/**
 * Model
 */
class Ownership extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'horizonstack_investment_ownerships';

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

<?php namespace Horizonstack\Investment\Models;

use Model;

/**
 * Model
 */
class InvestmentOrganization extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'horizonstack_investment_organizations';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required'
    ];

    public $attachOne = [
        'icon' => ['System\Models\File', 'delete' => true],
    ];

    public function scopeIsActive($query)
    {
        return $query->where('is_active', true);
    }
}

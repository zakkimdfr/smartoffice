<?php namespace Horizonstack\Investment\Models;

use Model;

/**
 * Model
 */
class InvestmentInterest extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'horizonstack_investment_investment_interests';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'  => 'required',
        'email' => 'required|email|unique:horizonstack_investment_investment_interests',
    ];

    public $fillable = [
        'investment_id',
        'name',
        'phone',
        'email',
        'organization_name',
    ];
}

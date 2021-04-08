<?php namespace Horizonstack\Investment\Models;

use Model;
use System\Models\File;

/**
 * Model
 */
class PolicyRegulation extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'horizonstack_investment_policy_regulations';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
    ];

    public $fillable = [
        'name',
        'investment_id',
    ];

    public $attachOne = [
        'file' => [
            File::class,
            'delete' => true,
        ],
    ];
}

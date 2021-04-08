<?php namespace Horizonstack\Location\Models;

use Model;

/**
 * Model
 */
class Village extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'horizonstack_location_villages';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'     => 'required',
        'district' => 'required',
    ];

    public $belongsTo = ['district' => District::class];
}

<?php namespace Horizonstack\Location\Models;

use Horizonstack\Guestbook\Models\City;
use Model;

/**
 * Model
 */
class District extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'horizonstack_location_districts';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
    ];

    public $belongsTo = ['city' => City::class];
}

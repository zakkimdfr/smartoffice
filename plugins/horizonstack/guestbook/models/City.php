<?php namespace Horizonstack\Guestbook\Models;

use Model;

/**
 * Model
 */
class City extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'horizonstack_guestbook_cities';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
    ];

    public $hasMany = [
        'guests' => [Guest::class, 'count' => true],
    ];

    public function scopeIsActive($query)
    {
        return $query->where('is_active', true);
    }
}

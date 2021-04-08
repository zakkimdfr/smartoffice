<?php namespace Horizonstack\Guestbook\Models;

use Model;

/**
 * Model
 */
class Organization extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'horizonstack_guestbook_organizations';

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

    public $hasMany = [
        'guests' => [Guest::class, 'count' => true],
    ];

    public $belongsToMany = [
        'events' => [
            Event::class,
            'table' => 'horizonstack_guestbook_event_organization'
        ]
    ];
}

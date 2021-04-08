<?php namespace Horizonstack\Guestbook\Models;

use Model;

/**
 * Model
 */
class Event extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'horizonstack_guestbook_events';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'     => 'required',
        'location' => 'required',
        'date'     => 'required',
    ];

    public $attachOne = [
        'image'        => ['System\Models\File', 'delete' => true],
        'meeting_note' => ['System\Models\File', 'delete' => true],
    ];

    public $attachMany = [
        'presentations' => ['System\Models\File', 'delete' => true],
    ];

    public $hasMany = [
        'guests' => Guest::class,
    ];

    public $belongsToMany = [
        'organizations' => [
            Organization::class,
            'table' => 'horizonstack_guestbook_event_organization',
        ],
    ];

    public function scopeIsActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeIsPublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeIsPrivate($query)
    {
        return $query->where('is_public', false);
    }
}

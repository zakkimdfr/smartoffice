<?php namespace Horizonstack\Guestbook\Models;

use Model;

/**
 * Model
 */
class Job extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'horizonstack_guestbook_jobs';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        'icon' => 'required',
    ];

    public $attachOne = [
        'icon' => ['System\Models\File', 'delete' => true],
    ];

    public $hasMany = [
        'guests' => [Guest::class, 'count' => true],
    ];

    public function scopeIsActive($query)
    {
        return $query->where('is_active', true);
    }
}

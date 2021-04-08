<?php namespace Horizonstack\eSPJ\Models;

use Model;
use RainLab\User\Models\User;

/**
 * Model
 */
class TripReport extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'horizonstack_espj_trip_reports';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'trip_id'       => 'required',
        'user_id'       => 'required',
        'report_number' => 'required',
        'attnTo'        => 'required',
        'From'          => 'required',
        'CC'            => 'required',
        'date'          => 'required',
        'attachment'    => 'required',
        'subject'       => 'required',
        'urgency_id'    => 'required',
        'description'   => 'required',
    ];

    public $fillable = [
        'trip_id',
        'user_id',
        'attn_to',
        'from',
        'cc',
        'date',
        'report_number',
        'urgency_id',
        'attachment',
        'subject',
        'description',
    ];

    public $attachOne = [
        'file_attachment' => ['System\Models\File', 'delete' => true],
    ];

    public $attachMany = [
        'photos' => ['System\Models\File', 'delete' => true],
    ];


    public $belongsTo = [
        'user'   => User::class,
        'trip'   => Trip::class,
        'attnTo' => [
            Attn::class,
            'key' => 'attn_to',
        ],
        'From'   => [
            Attn::class,
            'key' => 'from',
        ],
        'CC'     => [
            Attn::class,
            'key' => 'cc',
        ],
    ];

    public function getUrgencyIdOptions()
    {
        $urgencyList = [null => "Select Urgency"];

        $urgencies = Urgency::orderBy('name')->select('id', 'name')->get();

        foreach ($urgencies as $urgency) {
            $urgencyList[$urgency->id] = $urgency->name;
        }

        return $urgencyList;
    }

    public function getUserIdOptions()
    {
        $usersList = [null => "Select User"];

        $users = User::orderBy('name')->select('id', 'name')->get();

        foreach ($users as $user) {
            $usersList[$user->id] = $user->name;
        }

        return $usersList;
    }

}

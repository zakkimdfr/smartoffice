<?php namespace Horizonstack\Workreport\Models;

use Model;
use RainLab\User\Models\User;

/**
 * Model
 */
class Report extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'horizonstack_workreport_reports';

    /**
     * @var array Validation rules
     */
    public $rules = [
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

    public $belongsTo = [
        'user' => User::class,
        'attnTo' => [
            ReportAttn::class,
            'key' => 'attn_to',
        ],
        'From'   => [
            ReportAttn::class,
            'key' => 'from',
        ],
        'CC'     => [
            ReportAttn::class,
            'key' => 'cc',
        ],
    ];

    public $attachOne = [
        'file_attachment' => ['System\Models\File', 'delete' => true],
    ];

    public $attachMany = [
        'photos' => ['System\Models\File', 'delete' => true],
    ];

    public function getUrgencyIdOptions()
    {
        $urgencyList = [null => "Select Urgency"];

        $urgencies = ReportUrgency::orderBy('name')->select('id', 'name')->get();

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

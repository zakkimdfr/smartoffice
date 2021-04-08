<?php namespace ThePhuc\Tunsurvey\Models;

use Model;
use ThePhuc\Tunsurvey\Models\Question;
/**
 * Model
 */
class Survey extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    const DRAFT   = 1;
    const PUBLISH = 2;
    const CLOSED  = 3;

    const STATUS = [
        self::DRAFT   => 'Draft',
        self::PUBLISH => 'Publish',
        self::CLOSED  => 'Closed',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'thephuc_tunsurvey_surveys';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'   => 'required',
        'status' => 'required',
    ];

    public $attachOne = [
        'banner' => 'System\Models\File'
    ];

    public $hasMany = [
        'questions' => [
            'ThePhuc\Tunsurvey\Models\Question',
        ],
        'useranswers' => [
            'ThePhuc\Tunsurvey\Models\UserAnswer',
        ],
    ];

    public function getStatusOptions() {
        return self::STATUS;
    }

    public function status()
    {
        $class  = '';
        $status = isset(self::STATUS[$this->status]) ? self::STATUS[$this->status] : 'N/A';

        switch ($this->status) {
            case self::DRAFT:
                $class = 'lbl-primary';
                break;
            case self::PUBLISH:
                $class = 'lbl-success';
                break;
            case self::CLOSED:
                $class = 'lbl-danger';
                break;

            default:
                break;
        }

        return '<label class="'.$class.'">'.$status.'</label>';
    }

    public function afterCreate()
    {
        if (! $this->code) {
            $this->code = 'SUV' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
            $this->save();
        }
    }
}

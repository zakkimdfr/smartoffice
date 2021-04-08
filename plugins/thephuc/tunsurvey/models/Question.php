<?php namespace ThePhuc\Tunsurvey\Models;

use Model;

/**
 * Model
 */
class Question extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;
    use \October\Rain\Database\Traits\Sortable;

    protected $dates = ['deleted_at'];

    const TEXT     = 1;
    const TEXTAREA = 2;
    const DROPDOWN = 3;
    const RADIO    = 4;
    const CHECKBOX = 5;

    const CONTROL = [
        self::TEXT     => 'Text',
        self::TEXTAREA => 'Textarea',
        self::DROPDOWN => 'Dropdown',
        self::RADIO    => 'Radio',
        self::CHECKBOX => 'Checkbox',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'thephuc_tunsurvey_questions';

    public $hasMany = [
        'answers' => [
            'ThePhuc\Tunsurvey\Models\Answer',
        ],
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'question' => 'required',
    ];

    public function control() {
        return isset(self::CONTROL[$this->control]) ? self::CONTROL[$this->control] : 'N/A';
    }

    public function getControlOptions() {
        return self::CONTROL;
    }

    public function afterCreate()
    {
        if (! $this->code) {
            $this->code = 'QUE' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
            $this->save();
        }
    }

    public static function isValue($id)
    {
        $model = self::find($id);

        return in_array($model->control, [self::TEXT, self::TEXTAREA]);
    }
}

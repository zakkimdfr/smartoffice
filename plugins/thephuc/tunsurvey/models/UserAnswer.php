<?php namespace ThePhuc\Tunsurvey\Models;

use Model;
use ThePhuc\Tunsurvey\Models\Question;

/**
 * Model
 */
class UserAnswer extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'thephuc_tunsurvey_useranswers';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'survey_id' => 'required',
        'user_ip'   => 'required',
        'content'   => 'required',
    ];

    protected $fillable = [
        'survey_id', 'user_ip', 'content',
    ];

    public static function hasAnswer($survey_id, $user_ip)
    {
        return self::where('survey_id', $survey_id)
            ->where('user_ip', $user_ip)
            ->exists();
    }
}

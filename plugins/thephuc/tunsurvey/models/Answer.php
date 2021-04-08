<?php namespace ThePhuc\Tunsurvey\Models;

use Model;

/**
 * Model
 */
class Answer extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];
    /**
     * @var string The database table used by the model.
     */
    public $table = 'thephuc_tunsurvey_answers';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'answer' => 'required',
    ];

    protected $fillable = [
        'answer', 'question_id',
    ];

    public function afterCreate()
    {
        if (! $this->code) {
            $this->code = 'ANS' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
            $this->save();
        }
    }

    public static function getAnswer($ids)
    {
        $query = self::query();

        switch (gettype($ids)) {
            case 'string':
            case 'integer':
                $query->where('id', $ids);
                break;

            case 'array':
                $query->whereIn('id', $ids);
                break;

            default:
                break;
        }

        $data = $query->get();

        $result = '';
        foreach ($data as $item) {
            $result .= $item->answer . '<br>';
        }

        return $result;
    }
}

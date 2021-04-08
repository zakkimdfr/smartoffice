<?php namespace ThePhuc\Tunsurvey;

use System\Classes\PluginBase;
use ThePhuc\Tunsurvey\Models\Survey;
use ThePhuc\Tunsurvey\Models\Question;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'ThePhuc\Tunsurvey\Components\FormSurvey' => 'formsurvey',
        ];
    }

    public function registerListColumnTypes()
    {
        return [
            'image'       => [$this, 'image'],
            'status'      => [$this, 'status'],
            'control'     => [$this, 'control'],
            'user-answer' => [$this, 'userAnswer'],
        ];
    }

    public function userAnswer($value, $column, $record) {
        $users = $record->useranswers->count() ?? 0;

        return number_format($users) . ' ' . str_plural('user', $users);
    }

    public function image($value, $column, $record) {
        return $value ? '<img src="'.$value->getThumb(100, 50).'" />' : 'N/A';
    }

    public function status($value, $column, $record) {
        return $record->status() ?? 'N/A';
    }

    public function control($value, $column, $record) {
        return $record->control() ?? 'N/A';
    }

}

<?php namespace ThePhuc\Tunsurvey\Controllers;

use Request;
use BackendMenu;
use ThePhuc\Tunsurvey\Controllers\BaseController;
use ThePhuc\Tunsurvey\Models\Question;
use ThePhuc\Tunsurvey\Models\Answer;

class Surveys extends BaseController
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\RelationController',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';

    public $requiredPermissions = [
        'thephuc.tunsurvey.surveys_management'
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('ThePhuc.Tunsurvey', 'main-menu-item', 'side-menu-item');

        $this->addJs(url('/plugins/thephuc/tunsurvey/assets/js/Sortable.min.js'));
        $this->addJs(url('/plugins/thephuc/tunsurvey/assets/js/sortable.js'));
    }

    public function onReorder()
    {
        $records = Request::input('rcd');
        $model   = new Question;
        $model->setSortableOrder($records, range(1, count($records)));
    }

    public function onLoadCreateAnswerForm()
    {
        return $this->makePartial('answer_create_form', [
            'manage_id' => post('manage_id'),
            'answerFormWidget' => $this->createFormWidget(
                '$/thephuc/tunsurvey/models/answer/fields.yaml',
                new Answer
            ),
        ]);
    }

    public function onCreateAnswer()
    {
        Answer::create([
            'answer' => post('answer'),
            'status' => post('status'),
            'question_id' => post('manage_id'),
        ]);

        return $this->refreshAnswerList(post('manage_id'));
    }

    public function onDeleteAnswer()
    {
        Answer::find(post('record_id'))->delete();

        return $this->refreshAnswerList(post('manage_id'));
    }

    public function refreshAnswerList($manage_id)
    {
        return [
            '#answerList' => $this->makePartial('answer_list', [
                'answers' => Answer::whereQuestionId($manage_id)->get(),
            ])
        ];
    }
}

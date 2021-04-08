<?php namespace ThePhuc\Tunsurvey\Controllers;

use BackendMenu;
use ThePhuc\Tunsurvey\Controllers\BaseController;
use ThePhuc\Tunsurvey\Models\Survey;
use ThePhuc\Tunsurvey\Models\UserAnswer;
use ThePhuc\Tunsurvey\Models\Question;
use ThePhuc\Tunsurvey\Models\Answer;

class Analytics extends BaseController
{
    public $implement = [
        'Backend\Behaviors\ListController',
    ];

    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = [
        'thephuc.tunsurvey.survey_results_management'
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('ThePhuc.Tunsurvey', 'main-menu-item', 'side-menu-item3');
        $this->pageTitle = trans('thephuc.tunsurvey::lang.menu.survey_result');
    }

    public function preview($id) {
        $this->prepareVars($id);
    }

    public function onDeleteAnswer()
    {
        UserAnswer::where('id', post('record_id'))->delete();
        $this->prepareVars(post('survey_id'));

        return ['#list_answers' => $this->makePartial('$/thephuc/tunsurvey/controllers/analytics/_list_preview.htm')];
    }

    public function onShowDetail()
    {
        $this->addJs('https://code.highcharts.com/highcharts.js');
        $this->addJs('https://code.highcharts.com/modules/exporting.js');
        $this->addJs('https://code.highcharts.com/modules/export-data.js');

        $question = Question::find(post('question_id'));
        $uanswers = UserAnswer::where('survey_id', post('survey_id'))->get();
        $answers  = Answer::where('question_id', $question->id)->get();
        $results  = [];
        $isChart  = false;

        if (! Question::isValue($question->id)) {
            $isChart  = true;
            foreach ($answers as $answer) {
                $temp = ['answer' => $answer->answer, 'count' => 0];
                foreach ($uanswers as $ans) {
                    $content = (array) json_decode($ans->content);
                    $content = isset($content[post('question_id')]) ? $content[post('question_id')] : [];
                    $content = gettype($content) === 'array' ? $content : [$content];
                    if (in_array($answer->id, $content)) {
                        $temp['count']++;
                    }
                }
                $results[] = $temp;
            }
        } else {
            foreach ($uanswers as $ans) {
                $content   = (array) json_decode($ans->content);
                $content   = isset($content[post('question_id')]) ? $content[post('question_id')] : '';
                $results[] = $content;
            }
        }

        $this->vars['results'] = $results;
        $this->vars['isChart'] = $isChart;
        $this->vars['questionName'] = $question->question;

        return $this->makePartial('$/thephuc/tunsurvey/controllers/analytics/_detail_answer.htm');
    }

    private function prepareVars($id)
    {
        $survey = Survey::find($id);

        $this->vars['id'] = $survey->id;
        $this->vars['code'] = $survey->code;
        $this->vars['questions'] = $survey->questions;
        $this->vars['useranswers'] = $survey->useranswers;
    }
}

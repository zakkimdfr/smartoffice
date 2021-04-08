<?php namespace ThePhuc\Tunsurvey\Components;

use Request, Session;
use Cms\Classes\ComponentBase;
use ThePhuc\Tunsurvey\Models\Survey;
use ThePhuc\Tunsurvey\Models\UserAnswer;

class FormSurvey extends ComponentBase
{
    const BACKGROUND    = 'background: url(/plugins/thephuc/tunsurvey/assets/form/images/background.jpg) no-repeat center center; background-size: cover;';
    const BANNER_WIDTH  = 285;
    const BANNER_HEIGHT = 480;

    const checking_IP = 1;
    const checking_SESSION = 2;

    public function componentDetails()
    {
        return [
            'name' => 'thephuc.tunsurvey::lang.component.name',
            'description' => 'thephuc.tunsurvey::lang.component.description',
        ];
    }

    public function defineProperties()
    {
        $surveys = Survey::where('status', Survey::PUBLISH)->lists('name', 'id');

        return [
            'active_survey' => [
                'title'       => 'Active survey',
                'type'        => 'dropdown',
                'placeholder' => 'Select units',
                'options'     => $surveys,
            ],
            'checking_by' => [
                'title'       => 'User checking answered by',
                'type'        => 'dropdown',
                'options'     => [
                    self::checking_IP => 'Ip',
                    self::checking_SESSION => 'Session',
                ],
            ],
            'background' => [
                'title'       => 'css background',
                'Description' => 'Config background form survey',
                'default'     => self::BACKGROUND,
            ],
            'banner_width' => [
                'title'       => 'Width of banner',
                'Description' => 'Config width of banner',
                'default'     => self::BANNER_WIDTH,
            ],
            'banner_height' => [
                'title'       => 'Height of banner',
                'Description' => 'Config height of banner',
                'default'     => self::BANNER_HEIGHT,
            ],
        ];
    }

    public $survey;
    public $banner;
    public $answered;
    public $background;

    public function onRun()
    {
        $this->assets();
        $this->survey = $this->loadSurvey($this->property('active_survey'));
        $this->banner = [
            'w' => $this->property('banner_width'),
            'h' => $this->property('banner_height'),
        ];
        $this->background = $this->property('background');
        if ((int) $this->property('checking_by') === self::checking_IP) {
            $this->answered = UserAnswer::hasAnswer($this->survey->id ?? 0, Request::ip());
        } else {
            $this->answered = (bool) Session::get('answered', false);
        }
    }

    public function onSubmit()
    {
        $this->makeValidate(post('survey_id'), post('answer'));
        Session::put('answered', 'true');
        UserAnswer::create([
            'survey_id' => post('survey_id'),
            'user_ip'   => Request::ip(),
            'content'   => json_encode(post('answer')),
        ]);
    }

    private function makeValidate($survey_id, $params)
    {
        $survey = Survey::find($survey_id);

        foreach ($survey->questions as $question) {
            if (! $question->is_optional) {
                $field = isset($params[$question->id]) ? $params[$question->id] : null;
                if (! $field) {
                    throw new \ValidationException([
                        'error' => trans('thephuc.tunsurvey::lang.errors.required'),
                    ]);
                }
            }
        }
    }

    private function loadSurvey($id) {
        return Survey::where('status', Survey::PUBLISH)
            ->where('id', $id)
            ->first();
    }

    private function assets()
    {
        $this->addCss('https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css');
        $this->addCss('/plugins/thephuc/tunsurvey/assets/form/css/style.css');
        $this->addJs('/plugins/thephuc/tunsurvey/assets/form/js/main.js');
    }
}
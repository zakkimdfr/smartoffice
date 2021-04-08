<?php namespace ThePhuc\Tunsurvey\Controllers;

use Backend\Classes\Controller;

class BaseController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->addCss(url('/plugins/thephuc/tunsurvey/assets/css/style.css'));
    }

    public function createFormWidget($fields, $model)
    {
        $config = $this->makeConfig($fields);
        $config->model = $model;
        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->bindToController();

        return $widget;
    }
}
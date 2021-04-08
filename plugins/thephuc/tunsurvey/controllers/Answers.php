<?php namespace ThePhuc\Tunsurvey\Controllers;

use ThePhuc\Tunsurvey\Controllers\BaseController;
use BackendMenu;

class Answers extends BaseController
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'thephuc.tunsurvey.surveys_management'
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('ThePhuc.Tunsurvey', 'main-menu-item', 'side-menu-item');
    }
}

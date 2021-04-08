<?php namespace ThePhuc\Tunsurvey\Controllers;

use BackendMenu;
use ThePhuc\Tunsurvey\Controllers\BaseController;

class Questions extends BaseController
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
    }
}

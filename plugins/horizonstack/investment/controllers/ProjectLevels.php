<?php namespace Horizonstack\Investment\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class ProjectLevels extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'horizonstack.investment.manage_project_levels' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Horizonstack.Investment', 'investments-plugin', 'project-levels');
    }
}

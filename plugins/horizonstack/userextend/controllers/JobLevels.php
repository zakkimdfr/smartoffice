<?php namespace Horizonstack\Userextend\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class JobLevels extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'horizonstack.userextend.manage_job_levels' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('RainLab.User', 'user', 'joblevels');
    }
}

<?php namespace Horizonstack\Userextend\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class SubDepartments extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'horizonstack.userextend.manage_sub_departments' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('RainLab.User', 'user', 'subdepartments');
    }
}

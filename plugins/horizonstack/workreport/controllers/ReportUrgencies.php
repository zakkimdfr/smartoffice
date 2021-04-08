<?php namespace Horizonstack\Workreport\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class ReportUrgencies extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'horizonstack.workreport.manage_urgencies' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Horizonstack.Workreport', 'work-reports', 'report-urgencies');
    }
}

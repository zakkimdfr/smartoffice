<?php namespace Horizonstack\Workreport\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class ReportAttns extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'horizonstack.workreport.manage_attns',
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Horizonstack.Workreport', 'work-reports', 'attn');
    }
}

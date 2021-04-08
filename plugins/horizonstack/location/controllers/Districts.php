<?php namespace Horizonstack\Location\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Districts extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'horizonstack.location.manage_districts' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Horizonstack.Location', 'locations', 'districts');
    }
}

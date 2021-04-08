<?php namespace Horizonstack\Investment\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class PropertyTypes extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'horizonstack.investment.manage_property_types' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Horizonstack.Investment', 'investments-plugin', 'property-types');
    }
}

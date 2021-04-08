<?php namespace Horizonstack\Investment\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Ownerships extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'horizonstack.investment.manage_ownerships' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Horizonstack.Investment', 'investments-plugin', 'ownerships');
    }
}

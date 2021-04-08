<?php namespace Horizonstack\eSPJ\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class UrgencyController extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'horizonstack.espj.manage_urgency' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Horizonstack.eSPJ', 'eSPJ', 'urgency');
    }
}

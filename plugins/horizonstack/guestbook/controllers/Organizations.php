<?php namespace Horizonstack\Guestbook\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Organizations extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'horizonstack.guestbook.manage_organizations' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Horizonstack.Guestbook', 'guests', 'organizations');
    }
}

<?php namespace Horizonstack\Guestbook\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Jobs extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'horizonstack.guestbook.manage_jobs' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Horizonstack.Guestbook', 'guests', 'jobs');
    }
}

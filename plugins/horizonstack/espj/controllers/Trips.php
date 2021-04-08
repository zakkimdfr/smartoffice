<?php namespace Horizonstack\eSPJ\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Trips extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\RelationController',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';

    public $requiredPermissions = [
        'horizonstack.espj.manage_trips',
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Horizonstack.eSPJ', 'eSPJ', 'trips');
    }
}

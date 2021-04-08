<?php namespace Horizonstack\Investment\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Investments extends Controller
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
        'horizonstack.investment.manage_investments',
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Horizonstack.Investment', 'investments-plugin', 'investments');
    }
}

<?php namespace Horizonstack\Asset\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class AssetRecords extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'horizonstack.asset.manage_records' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Horizonstack.Asset', 'asset-plugin', 'records');
    }
}

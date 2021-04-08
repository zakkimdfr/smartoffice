<?php namespace ThePhuc\Tunsurvey\Controllers;

use Flash;
use BackendMenu;
use ThePhuc\Tunsurvey\Controllers\BaseController;
use ThePhuc\Tunsurvey\Models\Survey;

class Settings extends BaseController
{
    public $implement = [];

    const STYLESHEET   = __DIR__.'/../assets/form/css/style.css';
    const DEFAULT      = __DIR__.'/../components/formsurvey/default.htm';
    const _FORM_FIELDS = __DIR__.'/../components/formsurvey/_form_fields.htm';
    const _COMPLETED   = __DIR__.'/../components/formsurvey/_completed.htm';

    public $requiredPermissions = [
        'thephuc.tunsurvey.settings'
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('ThePhuc.Tunsurvey', 'main-menu-item', 'side-menu-item2');

        $this->pageTitle = trans('thephuc.tunsurvey::lang.menu.settings');
    }

    public function index()
    {
        $formWidget = $this->createFormWidget('$/thephuc/tunsurvey/models/settings/fields.yaml', new Survey);
        $formWidget->setFormValues([
            'stylesheet'   => @file_get_contents(self::STYLESHEET),
            'default'      => @file_get_contents(self::DEFAULT),
            '_form_fields' => @file_get_contents(self::_FORM_FIELDS),
            '_completed'   => @file_get_contents(self::_COMPLETED),
        ]);

        $this->vars['form'] = $formWidget;
    }

    public function onSaveSetting()
    {
        @file_put_contents(self::STYLESHEET,   post('stylesheet'));
        @file_put_contents(self::DEFAULT,      post('default'));
        @file_put_contents(self::_FORM_FIELDS, post('_form_fields'));
        @file_put_contents(self::_COMPLETED,   post('_completed'));

        Flash::success(trans('thephuc.tunsurvey::lang.message.setting_done'));
    }
}

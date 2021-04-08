<?php namespace Horizonstack\Userextend\FormWidgets;

use Html;
use Backend\Classes\FormWidgetBase;
use Horizonstack\Userextend\Models\Setting;

/**
 * Address finder
 * Renders a Google Place address field.
 *
 * Usage:
 *
 *   address:
 *       label: Address
 *       type: addressfinder
 *       fieldMap:
 *           latitude: latitude
 *           longitude: longitude
 *           city: city
 *           zip: zip
 */
class UserAddressFinder extends FormWidgetBase
{
    /**
     * {@inheritDoc}
     */
    public $defaultAlias = 'user_addressfinder';

    protected $fieldMap;

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        $this->fieldMap = $this->getConfig('fieldMap', []);
    }

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        $this->prepareVars();

        return $this->makePartial('user_addressfinder');
    }

    /**
     * Prepares the list data
     */
    public function prepareVars()
    {
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = $this->getLoadValue();
        $test = $this->formField->getAttributes();
        $this->vars['field'] = $this->formField;
    }

    public function getFieldMapAttributes()
    {
        $widget = $this->controller->formGetWidget();
        $fields = $widget->getFields();
        $result = [];

        foreach ($this->fieldMap as $map => $fieldName) {
            if (!$field = array_get($fields, $fieldName)) {
                $result['data-input-' . $fieldName] = '#Form-relationBranchesManageForm-field-Branch-' . $fieldName;
            } else {
                $result['data-input-' . $map] = '#' . $field->getId();
            }
        }

        return Html::attributes($result);
    }

    /**
     * {@inheritDoc}
     */
    public function loadAssets()
    {
        $apiKey = Setting::get('google_maps_key');
        $this->addJs('//maps.googleapis.com/maps/api/js?libraries=places&key=' . $apiKey);
        $this->addJs('js/location-autocomplete.js', 'core');
    }
}

<?php namespace Horizonstack\Asset\Components;

use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use Horizonstack\Asset\Models\AssetCategory;
use Horizonstack\Asset\Models\Record;

class RecordList extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Record List',
            'description' => 'Render list of assets records...'
        ];
    }

    public function defineProperties()
    {
        return [
        ];
    }
}

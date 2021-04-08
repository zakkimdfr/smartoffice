<?php namespace Horizonstack\Asset;

use Horizonstack\Asset\Components\AssetList;
use Horizonstack\Asset\Components\ManageAssets;
use Horizonstack\Asset\Components\RecordList;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            RecordList::class   => 'recordList',
            AssetList::class    => 'assetList',
            ManageAssets::class => 'manageAssets',
        ];
    }

    public function registerSettings()
    {
    }
}

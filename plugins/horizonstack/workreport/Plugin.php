<?php namespace Horizonstack\Workreport;

use Horizonstack\WorkReport\Components\CreateWorkReport;
use Horizonstack\WorkReport\Components\WorkReportList;
use Horizonstack\WorkReport\Components\WorkReportPrintDetail;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            WorkReportList::class => 'workReportList',
            CreateWorkReport::class => 'createWorkReport',
            WorkReportPrintDetail::class => 'workReportPrintDetail',
        ];
    }

    public function registerSettings()
    {
    }

    public function registerMailTemplates()
    {
        return [
            'horizonstack.workreport::mail.add-work-report',
        ];
    }
}

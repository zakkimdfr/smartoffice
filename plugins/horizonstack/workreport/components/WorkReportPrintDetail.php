<?php namespace Horizonstack\WorkReport\Components;

use Cms\Classes\ComponentBase;
use Horizonstack\eSPJ\Models\TripReport;
use Horizonstack\Workreport\Models\Report;
use Illuminate\Support\Facades\Redirect;

class WorkReportPrintDetail extends ComponentBase
{
    public $workReport;

    public function componentDetails()
    {
        return [
            'name'        => 'Work Report Print Detail',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function init()
    {
        $id = $this->param('id');

        $this->workReport = Report::with([
            'user',
            'user.jobLevel',
            'attnTo',
            'From',
            'CC',
        ])->find($id);
    }

    public function onRun()
    {
        if (empty($this->workReport)) {
            return Redirect::to('404');
        }

        $this->page['workReport'] = $this->workReport;
    }
}

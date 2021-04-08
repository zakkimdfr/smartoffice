<?php namespace Horizonstack\ESPJ\Components;

use Cms\Classes\ComponentBase;
use Horizonstack\eSPJ\Models\TripReport;
use Illuminate\Support\Facades\Redirect;

class TripReportPrintDetail extends ComponentBase
{
    public $tripReport;

    public function componentDetails()
    {
        return [
            'name'        => 'Trip Report Print Detail',
            'description' => 'No description provided yet...',
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function init()
    {
        $id = $this->param('id');

        $this->tripReport = TripReport::with([
            'user',
            'user.jobLevel',
            'trip',
            'attnTo',
            'From',
            'CC',
        ])->find($id);
    }

    public function onRun()
    {
        if (empty($this->tripReport)) {
            return Redirect::to('404');
        }

        $this->page['tripReport'] = $this->tripReport;
    }
}

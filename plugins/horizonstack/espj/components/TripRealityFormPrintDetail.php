<?php namespace Horizonstack\ESPJ\Components;

use Cms\Classes\ComponentBase;
use Horizonstack\eSPJ\Models\RealityForm;
use Illuminate\Support\Facades\Redirect;

class TripRealityFormPrintDetail extends ComponentBase
{
    public $realityForm;

    public function componentDetails()
    {
        return [
            'name'        => 'Trip Reality Form Print Detail',
            'description' => 'Render all details about particular Reality Form.',
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function init()
    {
        $id = $this->param('id');

        $this->realityForm = RealityForm::with([
            'user',
            'user.jobLevel',
            'trip',
            'trip.destinationCity1',
            'trip.return_city',
            'trip.participants',
            'trip.transportation',
            'trip.participants.jobLevel',
            'signature_left',
            'signature_right',
        ])->find($id);
    }

    public function onRun()
    {
        if (empty($this->realityForm)) {
            return Redirect::to('404');
        }

        $this->page['realityForm'] = $this->realityForm;
    }
}

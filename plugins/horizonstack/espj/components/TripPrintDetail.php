<?php namespace Horizonstack\ESPJ\Components;

use Cms\Classes\ComponentBase;
use Horizonstack\eSPJ\Models\Trip;
use Illuminate\Support\Facades\Redirect;

class TripPrintDetail extends ComponentBase
{
    public $trip;

    public function componentDetails()
    {
        return [
            'name'        => 'Trip Print Detail',
            'description' => 'Render all details about particular trip.',
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function init()
    {
        $slug = $this->param('slug');

        $this->trip = Trip::with([
            'user',
            'user.jobLevel',
            'origin_city',
            'destinationCity1',
            'return_city',
            'participants',
            'participants.jobLevel',
        ])->where('slug', $slug)->first();
    }

    public function onRun()
    {
        if (empty($this->trip)) {
            return Redirect::to('404');
        }

        $this->page['trip'] = $this->trip;
    }

}

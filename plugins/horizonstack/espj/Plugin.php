<?php namespace Horizonstack\eSPJ;

use Horizonstack\ESPJ\Components\CreateRealityForm;
use Horizonstack\ESPJ\Components\CreateReportForm;
use Horizonstack\Espj\Components\MobileTripCreateForm;
use Horizonstack\ESPJ\Components\TripCreateForm;
use Horizonstack\ESPJ\Components\TripList;
use Horizonstack\ESPJ\Components\TripPrintDetail;
use Horizonstack\ESPJ\Components\TripRealityFormPrintDetail;
use Horizonstack\ESPJ\Components\TripRealityFormsList;
use Horizonstack\ESPJ\Components\TripReportPrintDetail;
use Horizonstack\ESPJ\Components\TripReportsList;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            TripCreateForm::class             => 'tripCreateForm',
            TripList::class                   => 'tripList',
            TripPrintDetail::class            => 'tripPrintDetail',
            CreateRealityForm::class          => 'createRealityForm',
            CreateReportForm::class           => 'createReportForm',
            TripReportsList::class            => 'tripReportsList',
            TripRealityFormsList::class       => 'tripRealityFormsList',
            TripRealityFormPrintDetail::class => 'tripRealityFormPrintDetail',
            TripReportPrintDetail::class      => 'tripReportPrintDetail',
            MobileTripCreateForm::class       => 'mobileTripCreateForm',
        ];
    }

    public function registerSettings()
    {
    }

    public function registerMailTemplates()
    {
        return [
            'horizonstack.espj::mail.add-trip-reality',
            'horizonstack.espj::mail.add-trip-report',
            'horizonstack.espj::mail.propose-trip',
        ];
    }
}

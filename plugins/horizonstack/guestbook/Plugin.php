<?php namespace Horizonstack\Guestbook;

use Horizonstack\GuestBook\Components\DownloadEventGuestsPDF;
use Horizonstack\GuestBook\Components\EventDetails;
use Horizonstack\Guestbook\Components\GuestbookStats;
use Horizonstack\GuestBook\Components\GuestForm;
use Horizonstack\GuestBook\Components\ListEvents;
use Horizonstack\Guestbook\Components\ListPublicEvents;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            ListEvents::class             => 'listEvents',
            EventDetails::class           => 'eventDetails',
            GuestForm::class              => 'guestForm',
            DownloadEventGuestsPDF::class => 'downloadEventGuestsPDF',
            ListPublicEvents::class       => 'listPublicEvents',
            GuestbookStats::class         => 'guestBookStats',
        ];
    }

    public function registerSettings()
    {
    }

    public function registerMailTemplates()
    {
        return [
            'horizonstack.guestbook::mail.mail-to-guest',
        ];
    }
}

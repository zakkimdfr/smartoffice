<?php namespace Horizonstack\GuestBook\Components;

use Cms\Classes\ComponentBase;
use Horizonstack\Guestbook\Models\Event;
use Horizonstack\Guestbook\Models\Guest;
use Redirect;
use Renatio\DynamicPDF\Classes\PDF;

class DownloadEventGuestsPDF extends ComponentBase
{
    public $event;

    public function componentDetails()
    {
        return [
            'name'        => 'Download Event Guests PDF',
            'description' => 'Download Event Guests PDF.',
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $id = $this->param('id');

        if ( ! empty($id)) {

            $this->event = Event::find($id);

            if (empty($this->event)) {
                return Redirect::to('404');
            }

            $guests = Guest::where('event_id', $this->event->id)->orderBy('created_at', 'desc')->get();

            $pdfData = [
                'guests' => $guests,
                'event' => $this->event,
            ];

            //$pdf_file = storage_path('temp/'.$this->event->name.'-guests'.'.pdf');
            $pdf = PDF::loadTemplate('export-event-guests', $pdfData)->download($this->event->slug.'-guests'.'.pdf');

            return $pdf;
        }

        return redirect()->to('404');
    }
}

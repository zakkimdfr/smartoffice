<?php namespace Horizonstack\Guestbook\Models;

use Backend\Models\ExportModel;
use Carbon\Carbon;

class EventExport extends ExportModel
{

    protected $exportFileName = 'itna.csv';

    public function exportData($columns, $sessionKey = null)
    {
        $eventId = get('eventId');

        $guests = Guest::where('event_id', $eventId)->orderBy('name', 'asc')->get();

        $guests->each(function ($guest) use ($columns) {
            $guest->job_name = $guest->job->name;
            $guest->event_name = $guest->event->name;
            $guest->created_at = Carbon::parse($guest->created_at)->setTimezone('Asia/Jakarta');
            $guest->addVisible($columns);
        });

        return $guests->toArray();
    }
}
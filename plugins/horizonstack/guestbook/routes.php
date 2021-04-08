<?php

Route::get('api/search/{keyword}', function ($keyword) {
    $data = $keyword;

    if ( ! empty($data)) {
        //Search events with matching keyword
        $events = \Horizonstack\Guestbook\Models\Event::select('name', 'slug')->where('name', 'like',
            "%$data%")->isActive()->orderBy('name', 'asc')->get();

        $events->each(function ($item, $key) {
            if ( ! empty($item)) {
                if ( ! empty($item->slug)) {
                    $item['url']        = url()->to('/detail/event/'.$item->slug);
                    $item['pluginType'] = "Event";
                }
            }
        });

        $events = $events->toArray();

        $results = array_merge($events);

        return $results;
    }
});

Route::get('api/search/public/event/{keyword}', function ($keyword) {
    $data = $keyword;

    if ( ! empty($data)) {
        //Search events with matching keyword
        $events = \Horizonstack\Guestbook\Models\Event::select('name', 'slug')->where('name', 'like',
            "%$data%")->isPublic()->isActive()->orderBy('name', 'asc')->get();

        $events->each(function ($item, $key) {
            if ( ! empty($item)) {
                if ( ! empty($item->slug)) {
                    $item['url']        = url()->to('/detail/event/public/'.$item->slug);
                    $item['pluginType'] = "Event";
                }
            }
        });

        $events = $events->toArray();

        $results = array_merge($events);

        return $results;
    }
});

Route::get('files/download/{file_id}',
    function (\Illuminate\Http\Request $request, $file_id) {

        if ( ! empty($file_id)) {

            $file = \System\Models\File::find($file_id);

            $headers = array(
                'Content-Type: '.$file->content_type,
            );

            return response()->download($file->getLocalPath(), $file->file_name, $headers);
        }
    });

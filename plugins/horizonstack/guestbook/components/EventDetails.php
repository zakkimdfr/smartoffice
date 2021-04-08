<?php namespace Horizonstack\GuestBook\Components;

use Cms\Classes\ComponentBase;
use Horizonstack\Guestbook\Models\Event;
use Horizonstack\Guestbook\Models\Guest;
use Illuminate\Support\Facades\Redirect;
use October\Rain\Argon\Argon;
use ValidationException;
use Flash;
use Exception;

class EventDetails extends ComponentBase
{
    public $event, $guests;

    public function componentDetails()
    {
        return [
            'name'        => 'Event Details',
            'description' => 'Render all details about particular event..',
        ];
    }

    public function defineProperties()
    {
        return [
            'guestsPerPage' => [
                'title'             => 'Per Page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Numbers only.',
                'default'           => '15',
            ],
        ];
    }

    public function init()
    {
        $slug = $this->param('slug');

        $this->event = Event::with([
            'image',
            'meeting_note',
            'presentations',
        ])->where('slug', $slug)->first();
    }

    public function onRun()
    {
        if (empty($this->event)) {
            return Redirect::to('404');
        }

        if ($this->param('invitation_code') && $this->param('response')) {
            $invitationCode     = $this->param('invitation_code');
            $invitationResponse = $this->param('response');
            if ( ! empty($invitationCode) && ! empty($invitationResponse)) {
                /*
                * Break up the code parts
                */
                $parts = explode('!', $invitationCode);
                if (count($parts) != 2) {
                    Flash::error('Kode undangan yang Anda miliki tidak valid.');

                    if ($this->event->is_public == 1) {
                        return Redirect::to('/detail/event/public/'.$this->event->slug);
                    } else {
                        return Redirect::to('/detail/event/'.$this->event->slug);
                    }
                }

                list($guestId, $invitationCode) = $parts;

                if ( ! strlen(trim($guestId)) || ! ($guest = Guest::find($guestId))) {
                    Flash::error('Data tamu tidak ditemukan');

                    if ($this->event->is_public == 1) {
                        return Redirect::to('/detail/event/public/'.$this->event->slug);
                    } else {
                        return Redirect::to('/detail/event/'.$this->event->slug);
                    }
                }

                if (empty($guest->invitation_code)) {
                    Flash::error('Konfirmasi telah dilakukan');

                    if ($this->event->is_public == 1) {
                        return Redirect::to('/detail/event/public/'.$this->event->slug);
                    } else {
                        return Redirect::to('/detail/event/'.$this->event->slug);
                    }
                }

                $responses = ['yes', 'no'];

                if ( ! in_array($invitationResponse, $responses)) {
                    return Redirect::to('404');
                }

                $guest->invitation_code = null;

                if ($invitationResponse == 'yes') {
                    $guest->invitation_confirmation = Guest::INVITATION_CONFIRMATION_ACCEPTED;
                    $message                        = "Terimakasih atas konfirmasi kehadiran Anda.";
                }

                if ($invitationResponse == 'no') {
                    $guest->invitation_confirmation = Guest::INVITATION_CONFIRMATION_REJECTED;
                    $message                        = "Terima kasih, Anda dapat mengikuti kegiatan kami di lain waktu.";
                }

                $guest->invitation_responded_at = $this->freshTimestamp();
                $guest->forceSave();

                Flash::success($message);

                if ($this->event->is_public == 1) {
                    return Redirect::to('/detail/event/public/'.$this->event->slug);
                } else {
                    return Redirect::to('/detail/event/'.$this->event->slug);
                }
            }
        }

        $this->page['event'] = $this->event;

        $this->page['guests'] = $this->prepareVarForGuests();
    }

    public function prepareVarForGuests($searchTerm = null, $options = null)
    {
        if (empty($options['sort_by'])) {
            $options['sort_by'] = 'name';
        }

        if (empty($options['sort_direction'])) {
            $options['sort_direction'] = 'asc';
        }

        if (empty($options['page'])) {
            $page = $options['page'] = 1;
        } else {
            $page = $options['page'];
        }

        $this->page['page']           = $page;
        $this->page['sortField']      = $options['sort_by'];
        $this->page['sort_direction'] = $options['sort_direction'];

        return $guests = $this->onLoadGuests($page, $searchTerm, $options);
    }

    public function onLoadGuests($page = null, $name = false, $options = false)
    {
        $listGuests = Guest::where('event_id', $this->event->id)->with([
            'image',
            'job',
            'guestOrganization',
            'guestCity',
        ]);

        if ($name) {
            $listGuests->where('name', 'like', "%".$name."%");
        }

        if ( ! empty($options)) {
            if ($options['sort_by'] == 'department') {
                /*$sortBy = "wwj_accommodation_hotels.date";*/
            } else {
                $sortBy = $options['sort_by'];
            }
            $sortDirection = $options['sort_direction'];
        } else {
            $sortBy        = "name";
            $sortDirection = "asc";
        }

        $listGuests = $listGuests->orderBy($sortBy, $sortDirection);

        if ( ! empty($page)) {
            $listGuests = $listGuests->paginate($this->property('guestsPerPage'), $page);
        } else {
            $listGuests = $listGuests->paginate($this->property('guestsPerPage'), 1);
        }

        return $listGuests;
    }

    public function onPageChange()
    {
        $options = post();
        if ( ! empty(post('searchTerm'))) {
            $searchTerm = $options['searchTerm'];
        }

        if (empty($options['sort_by'])) {
            $options['sort_by'] = 'name';
        }

        if (empty($options['sort_direction'])) {
            $options['sort_direction'] = 'asc';
        }

        if ( ! empty($searchTerm)) {
            $guests                   = $this->prepareVarForGuests($searchTerm, $options);
            $this->page['searchTerm'] = $searchTerm;
        } else {
            $guests = $this->prepareVarForGuests(false, $options);
        }

        $this->page['guests'] = $guests;
    }

    public function onSearchGuests()
    {
        $options = post();

        /*if (empty($options['searchTerm'])) {
            throw new ValidationException(['searchTerm' => "Enter name or email to search User."]);
        }*/

        if ( ! empty(post('searchTerm'))) {
            $searchTerm = $options['searchTerm'];
        } else {
            $searchTerm = "";
        }

        $guests = $this->prepareVarForGuests($searchTerm, $options);

        $this->page['searchTerm'] = $searchTerm;

        $this->page['guests'] = $guests;

    }

    /**
     * Get a fresh timestamp for the model.
     *
     * @return \October\Rain\Argon\Argon
     */
    public function freshTimestamp()
    {
        return new Argon;
    }

    public function onOpenGuestDetail()
    {
        $guestId = post('guestId');

        if ( ! empty($guestId)) {
            $guest = Guest::with(['image', 'guestOrganization', 'guestOrganization', 'job'])->find($guestId);

            if ( ! empty($guest)) {
                $this->page['guestDetail'] = $guest;
            }
        }

    }
}

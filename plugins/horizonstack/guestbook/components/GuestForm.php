<?php namespace Horizonstack\GuestBook\Components;

use Cms\Classes\ComponentBase;
use Horizonstack\Guestbook\Models\City;
use Horizonstack\Guestbook\Models\Event;
use Horizonstack\Guestbook\Models\Guest;
use Horizonstack\Guestbook\Models\Job;
use Horizonstack\Guestbook\Models\Organization;
use http\Exception\RuntimeException;
use NetSTI\Uploader\Components\ImageUploader;
use Illuminate\Support\Facades\Validator;
use Flash;
use Auth;
use ValidationException;
use Mail;

class GuestForm extends ComponentBase
{
    public $event;

    public function componentDetails()
    {
        return [
            'name'        => 'Guest Form',
            'description' => 'No description provided yet...',
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function init()
    {
        $slug = $this->param('slug');

        $this->event = Event::where('slug', $slug)->first();

        $component = $this->addComponent(
            ImageUploader::class,
            'imageUploader',
            [
                'deferredBinding' => true,
                'fileTypes'       => ".gif,.jpg,.jpeg,.png",
                'imageMode'       => 'auto',
                'modelClass'      => 'Horizonstack\Guestbook\Models\Guest',
            ]
        );

        $component->bindModel('image', new Guest());
    }

    public function onRun()
    {
        $jobs               = Job::isActive()->orderBy('name')->get();
        $this->page['jobs'] = $jobs;

        $cities               = City::isActive()->orderBy('name', 'asc')->get();
        $this->page['cities'] = $cities;

        $organizations = Organization::isActive()->orderBy('name', 'asc');
        if ( ! empty($this->event)) {
            $organizations->whereHas('events', function ($query) {
                $query->where('id', $this->event->id);
            });
        }
        $organizations               = $organizations->get();
        $this->page['organizations'] = $organizations;

        $statuses               = [
            Guest::STATUS_INVITED    => 'Terundang',
            Guest::STATUS_REPRESENT  => 'Mewakili',
            Guest::STATUS_CO_INVITED => 'Mendampingi',
        ];
        $this->page['statuses'] = $statuses;
    }

    public function onAddGuest()
    {
        try {
            $data = post();

            $rules = [
                'organization_id' => 'required',
                'status'          => 'required',
                'job_title'       => 'required',
                'name'            => 'required',
                'phone'           => 'required',
                'email'           => 'required|email',
                'city_id'         => 'required',
                'job_id'          => 'required',
                'image'           => 'required',
            ];

            $messages = [
                'organization_id.required' => 'Nama Organisasi dieprlukan',
                'status.required'          => 'Status undangan dieprlukan',
                'job_title.required'       => 'Ketik Jabatan Anda',
                'name.required'            => 'Nama Anda diperlukan',
                'phone.required'           => 'Nomor Telepon dieprlukan',
                'email.required'           => 'Alamat email diperlukan',
                'city_id.required'         => 'Asal Kota/Kabupaten dieprlukan',
                'job_id.required'          => 'Mohon Pilih Profesi Anda',
                'image.required'           => 'Mohon Ambil Foto Diri',
            ];

            $validation = Validator::make($data, $rules, $messages);
            if ($validation->fails()) {
                throw new ValidationException($validation);
            }

            $img       = $data['image'];
            $img       = str_replace('data:image/jpeg;base64,', '', $img);
            $img       = str_replace(' ', '+', $img);
            $imageData = base64_decode($img);

            $file = (new \System\Models\File)->fromData($imageData, 'image-'.$data['name'].'.jpeg');

            $data['invitation_confirmation'] = Guest::INVITATION_CONFIRMATION_PENDING;
            $guest                           = new Guest();
            $guest->fill($data);
            $guest->save();

            if ( ! empty($file)) {
                $guest->image = $file;
                $guest->save();
            }

            if ( ! empty($guest)) {
                $guest['invitation_code'] = $this->getInvitationCode($guest);
                $guest->save();

                $guestMail = $guest->email;
                if ( ! empty($guestMail)) {
                    $acceptLink = $this->getAcceptLink($guest->invitation_code);
                    $rejectLink = $this->getRejectLink($guest->invitation_code);

                    $emailData = [
                        'guest'      => $guest,
                        'event'      => $this->event,
                        'acceptLink' => $acceptLink,
                        'rejectLink' => $rejectLink,
                    ];

                    Mail::send('horizonstack.guestbook::mail.mail-to-guest', $emailData,
                        function ($message) use ($guestMail) {
                            $message->to($guestMail);
                        });
                }
            }

        } catch (\Exception $exception) {
            if (\Illuminate\Support\Facades\Request::ajax()) {
                throw $exception;
            } else {
                Flash::error($exception->getMessage());
            }
        }
    }

    public function getAcceptLink($invitationCode)
    {
        if ($this->event->is_public == 1) {
            $url = env("APP_URL")."/detail/event/public/".$this->event->slug."/".$invitationCode."/yes";
        } else {
            $url = env("APP_URL")."/detail/event/".$this->event->slug."/".$invitationCode."/yes";
        }

        return $url;
    }

    public function getRejectLink($invitationCode)
    {
        if ($this->event->is_public == 1) {
            $url = env("APP_URL")."/detail/event/public/".$this->event->slug."/".$invitationCode."/no";
        } else {
            $url = env("APP_URL")."/detail/event/".$this->event->slug."/".$invitationCode."/no";
        }

        return $url;
    }

    public function getInvitationCode($guest)
    {
        $code = implode('!', [$guest->id, $this->getRandomString()]);

        return $code;
    }

    public function getRandomString($length = 42)
    {
        /*
         * Use OpenSSL (if available)
         */
        if (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($length * 2);

            if ($bytes === false) {
                throw new RuntimeException('Unable to generate a random string');
            }

            return substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $length);
        }

        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
}

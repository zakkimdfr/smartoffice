<?php namespace Horizonstack\ESPJ\Components;

use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use Horizonstack\eSPJ\Models\Signature;
use Horizonstack\eSPJ\Models\Transportation;
use Horizonstack\eSPJ\Models\Trip;
use Horizonstack\Guestbook\Models\City;
use Horizonstack\Userextend\Models\Setting;
use Illuminate\Support\Facades\Redirect;
use RainLab\User\Models\User;
use Auth;
use ValidationException;
use Validator;
use Flash;
use Mail;

class TripCreateForm extends ComponentBase
{
    public $user;

    public function componentDetails()
    {
        return [
            'name'        => 'Trip Create Form',
            'description' => 'No description provided yet...',
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function init()
    {
        $this->addCss('/themes/smartoffice/assets/vendor/summernote/dist/summernote-lite.css');

        $this->user = $this->user();
    }

    public function onRun()
    {
        $kotaBandungCity = City::find('3273');
        $this->page['kotaBandungCity'] = $kotaBandungCity;

        $transportations               = Transportation::isActive()->orderBy('name')->get();
        $this->page['transportations'] = $transportations;

        $cities               = City::isActive()->orderBy('name')->get();
        $this->page['cities'] = $cities;

        $signatures               = Signature::isActive()->orderBy('name')->get();
        $this->page['signatures'] = $signatures;

        $participants               = User::with('avatar')->where('id', '!=',
            $this->user->id)->isActivated()->orderBy('name')->get();
        $this->page['participants'] = $participants;
    }

    /**
     * Returns the logged in user, if available
     */
    public function user()
    {
        if ( ! Auth::check()) {
            return null;
        }

        return Auth::getUser();
    }

    public function onCreateTrip()
    {
        $data = post();

        $rules = [
            'name'               => 'required',
            'budget_name'        => 'required',
            'mission'            => 'required',
            'start_at'           => 'required',
            'return_at'          => 'required',
            'origin_city_id'     => 'required',
            'destination_city_1' => 'required',
            'return_city_id'     => 'required',
            'transportation_id'  => 'required',
            'signature_id'       => 'required',
            'signature_date'     => 'required',
        ];

        $messages = [

        ];

        $validation = Validator::make($data, $rules, $messages);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        if ($data['start_at']) {
            $data['start_at'] = Carbon::createFromFormat('d/m/Y', $data['start_at']);
        }

        if ($data['return_at']) {
            $data['return_at'] = Carbon::createFromFormat('d/m/Y', $data['return_at']);
        }

        if ($data['signature_date']) {
            $data['signature_date'] = Carbon::createFromFormat('d/m/Y', $data['signature_date']);
        }

        $data['user_id'] = $this->user->id;

        $trip = new Trip();
        $trip->fill($data);
        $trip->save();

        if ( ! empty($data['participants']) > 0) {
            $trip->participants()->sync($data['participants']);
        }

        $emails = Setting::get('emails');

        if ( ! empty($trip)) {
            $emailData = ['trip' => $trip];
            if ( ! empty($emails)) {
                $emails = explode(",", preg_replace('/\s+/', '', $emails));

                Mail::send('horizonstack.espj::mail.propose-trip', $emailData, function ($message) use ($emails) {
                    $message->to($emails);
                });
            }
        }


        Flash::success('Perjalanan Dinas Diajukan');

        return Redirect::to('/perjadin');
    }
}

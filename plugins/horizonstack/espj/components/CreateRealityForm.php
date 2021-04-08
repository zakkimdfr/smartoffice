<?php namespace Horizonstack\ESPJ\Components;

use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use Auth;
use Horizonstack\eSPJ\Models\RealityForm;
use Horizonstack\eSPJ\Models\Signature;
use Horizonstack\eSPJ\Models\Trip;
use Horizonstack\Userextend\Models\Setting;
use Illuminate\Support\Facades\Redirect;
use ValidationException;
use Flash;
use Mail;
use Validator;

class CreateRealityForm extends ComponentBase
{
    public $user;

    public function componentDetails()
    {
        return [
            'name'        => 'Create Reality Form',
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
        $trips = Trip::where(function ($query) {
            $query->where('user_id', $this->user->id)->orWhereHas('participants',
                function ($query) {
                    $query->where('user_id', $this->user->id);
                });
        })->orderBy('name')->get();

        $this->page['trips'] = $trips;

        $signatures               = Signature::isActive()->orderBy('name')->get();
        $this->page['signatures'] = $signatures;
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

    public function onSelectTrip()
    {
        $trip_id = post('trip_id');

        if ( ! empty($trip_id)) {
            $trip = Trip::with([
                'transportation',
                'origin_city',
                'destinationCity1',
                'participants',
                'participants.avatar',
                'user',
                'user.avatar',
            ])->find($trip_id);

            if ( ! empty($trip)) {
                $starDate = Carbon::parse($trip->start_at);
                $endDate  = Carbon::parse($trip->return_at);

                if ($starDate && $endDate) {
                    $diffDays       = $endDate->diffInDays($starDate);
                    $trip['days']   = $diffDays + 1;
                    $trip['nights'] = $diffDays;
                }

                $this->page['selectedTrip'] = $trip;
            }
        }
    }

    public function onCreateRealityForm()
    {
        $data = post();

        $rules = [
            'trip_id'            => 'required',
            'signature_left_id'  => 'required',
            'signature_right_id' => 'required',
        ];

        $messages = [

        ];

        $validation = Validator::make($data, $rules, $messages);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        $data['user_id'] = $this->user->id;

        $realityForm = new RealityForm();
        $realityForm->fill($data);
        $realityForm->save();

        $emails = Setting::get('emails');

        if ( ! empty($realityForm)) {
            $emailData = ['realityForm' => $realityForm, 'trip' => $realityForm->trip];
            if ( ! empty($emails)) {
                $emails = explode(",", preg_replace('/\s+/', '', $emails));

                Mail::send('horizonstack.espj::mail.add-trip-reality', $emailData, function ($message) use ($emails) {
                    $message->to($emails);
                });
            }
        }

        Flash::success('Surat Perjalanan Dinas berhasil dibuat!');

        return Redirect::to('/perjadin');
    }
}

<?php namespace Horizonstack\ESPJ\Components;

use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use Auth;
use Horizonstack\eSPJ\Models\Attn;
use Horizonstack\eSPJ\Models\Trip;
use Horizonstack\eSPJ\Models\TripReport;
use Horizonstack\eSPJ\Models\Urgency;
use Horizonstack\Userextend\Models\Setting;
use Illuminate\Support\Facades\Redirect;
use NetSTI\Uploader\Components\ImageUploader;
use System\Models\File;
use ValidationException;
use Validator;
use Flash;
use Input;
use Mail;

class CreateReportForm extends ComponentBase
{
    public $user;

    public function componentDetails()
    {
        return [
            'name'        => 'Create Report Form',
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

        $photos = $this->addComponent(
            ImageUploader::class,
            'photos',
            [
                'deferredBinding' => true,
                'fileTypes'       => ".gif,.jpg,.jpeg,.png",
                'imageMode'       => 'auto',
                'modelClass'      => TripReport::class,
            ]
        );

        $photos->bindModel('photos', new TripReport());
    }

    public function onRun()
    {
        $trips               = Trip::where('user_id', $this->user->id)->has('reports', '=', 0)->orderBy('name')->get();
        $this->page['trips'] = $trips;

        $attnOptions               = Attn::isActive()->orderBy('name')->get();
        $this->page['attnOptions'] = $attnOptions;

        $urgencyOptions               = Urgency::isActive()->orderBy('name')->get();
        $this->page['urgencyOptions'] = $urgencyOptions;
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

    public function onCreateReportForm()
    {
        $data = post();

        $rules = [
            'trip_id'       => 'required',
            'attn_to'       => 'required',
            'from'          => 'required',
            'cc'            => 'required',
            'date'          => 'required',
            'report_number' => 'required',
            'urgency_id'    => 'required',
            'attachment'    => 'required',
            'subject'       => 'required',
        ];

        $messages = [

        ];

        $validation = Validator::make($data, $rules, $messages);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        if ($data['date']) {
            $data['date'] = Carbon::createFromFormat('d/m/Y', $data['date']);
        }

        $data['user_id'] = $this->user->id;

        if ( ! empty(Input::file('file_attachment'))) {
            $file_attachment            = new File();
            $file_attachment->data      = Input::file('file_attachment');
            $file_attachment->is_public = true;
            $file_attachment->save();
        }

        $tripReport = new TripReport();
        $tripReport->fill($data);
        $tripReport->save(null, post('_session_key'));

        if ( ! empty($file_attachment) && ! empty($tripReport)) {
            $tripReport->file_attachment()->add($file_attachment);
        }

        $emails = Setting::get('emails');

        if ( ! empty($tripReport)) {
            $emailData = ['tripReport' => $tripReport, 'trip' => $tripReport->trip];
            if ( ! empty($emails)) {
                $emails = explode(",", preg_replace('/\s+/', '', $emails));

                Mail::send('horizonstack.espj::mail.add-trip-report', $emailData, function ($message) use ($emails) {
                    $message->to($emails);
                });
            }
        }

        Flash::success('Laporan Perjalanan Dinas berhasil dibuat');

        return Redirect::to('/perjadin');
    }
}

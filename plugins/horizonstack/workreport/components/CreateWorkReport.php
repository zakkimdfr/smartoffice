<?php namespace Horizonstack\WorkReport\Components;

use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use Horizonstack\eSPJ\Models\TripReport;
use Horizonstack\Userextend\Models\Setting;
use Horizonstack\Workreport\Models\Report;
use Horizonstack\Workreport\Models\ReportAttn;
use Horizonstack\Workreport\Models\ReportUrgency;
use Illuminate\Support\Facades\Redirect;
use NetSTI\Uploader\Components\ImageUploader;
use System\Models\File;
use Auth;
use ValidationException;
use Validator;
use Flash;
use Input;
use Mail;

class CreateWorkReport extends ComponentBase
{
    public $user;

    public function componentDetails()
    {
        return [
            'name'        => 'Create Work Report Form',
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

        $photos->bindModel('photos', new Report());
    }

    public function onRun()
    {
        $attnOptions               = ReportAttn::isActive()->orderBy('name')->get();
        $this->page['attnOptions'] = $attnOptions;

        $urgencyOptions               = ReportUrgency::isActive()->orderBy('name')->get();
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

    public function onCreateWorkReportForm()
    {
        $data = post();

        $rules = [
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

        $workReport = new Report();
        $workReport->fill($data);
        $workReport->save(null, post('_session_key'));

        if ( ! empty($file_attachment) && ! empty($workReport)) {
            $workReport->file_attachment()->add($file_attachment);
        }

        $emails = Setting::get('emails');

        if ( ! empty($workReport)) {
            $workReport = Report::with(['From', 'attnTo'])->find($workReport->id);
            $emailData  = ['workReport' => $workReport];
            if ( ! empty($emails)) {
                $emails = explode(",", preg_replace('/\s+/', '', $emails));

                Mail::send('horizonstack.workreport::mail.add-work-report', $emailData,
                    function ($message) use ($emails) {
                        $message->to($emails);
                    });
            }
        }

        Flash::success('Nota Dinas Berhasil Dibuat');

        return Redirect::to('/nota-dinas');
    }
}

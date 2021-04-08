<?php namespace Horizonstack\Userextend\Components;

use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use FontLib\Table\Type\post;
use Horizonstack\Asset\Models\Asset;
use Horizonstack\Asset\Models\Record;
use Auth;
use Illuminate\Support\Facades\Redirect;
use October\Rain\Support\Facades\Flash;

class UserUsedAssetList extends ComponentBase
{
    public $user;

    public function componentDetails()
    {
        return [
            'name'        => 'User Used Asset List Component',
            'description' => 'No description provided yet...',
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function init()
    {
        $this->user = $this->user();
    }

    public function onRun()
    {
        $this->loadAssets();
    }

    public function user()
    {
        if ( ! Auth::check()) {
            return null;
        }

        return Auth::getUser();
    }

    public function onReturnAsset()
    {
        $record_id = post('record_id');
        $record    = Record::find($record_id);

        if ( ! empty($record)) {
            $record->status   = Record::STATUS_RETURNED;
            $record->date_end = Carbon::now();
            $record->save();

            if ($asset = $record->asset) {
                $asset->status = Asset::STATUS_RETURNED;
                $asset->save();
            }
        }

        $this->loadAssets();
        Flash::success('Record set returned!');

        return Redirect::refresh();
    }

    public function loadAssets()
    {
        $recordList               = Record::with(['asset', 'asset.photos'])->where('user_id',
            $this->user->id)->orderBy('created_at',
            'desc')->get();
        $this->page['recordList'] = $recordList;
    }
}

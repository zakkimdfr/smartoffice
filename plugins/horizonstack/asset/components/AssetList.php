<?php namespace Horizonstack\Asset\Components;

use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use Horizonstack\Asset\Models\Asset;
use Horizonstack\Asset\Models\AssetCategory;
use Horizonstack\Asset\Models\Record;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Flash;
use ValidationException;
use Auth;

class AssetList extends ComponentBase
{
    public $user;

    public function componentDetails()
    {
        return [
            'name'        => 'AssetList Component',
            'description' => 'No description provided yet...',
        ];
    }

    public function defineProperties()
    {
        return [
            'assetsPerPage' => [
                'title'             => 'Per Page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Numbers only.',
                'default'           => '15',
            ],
            'sortOrder'     => [
                'title'       => 'List Order',
                'description' => 'Attribute on which the list should be ordered',
                'type'        => 'dropdown',
                'default'     => 'name asc',
            ],
        ];
    }

    public function getSortOrderOptions()
    {
        return Asset::$allowedSortingOptions;
    }

    public function prepareVars()
    {
        $assetCategories               = AssetCategory::isActive()->orderBy('name')->get();
        $this->page['assetCategories'] = $assetCategories;

        $years = [];
        for ($n = 2010; $n <= Carbon::now()->year; $n++) {
            $years[] = ['value' => $n];
        }

        $this->page['assetYears'] = $years;
    }

    public function init()
    {
        $this->page['user'] = $this->user = $this->user();
    }

    public function onRun()
    {
        $this->onChangeFilter();
        $this->prepareVars();
    }

    public function onChangeFilter($page = null)
    {
        $data = post();

        $assets = Asset::with(['photos', 'category', 'type'])->isActive();

        if ( ! empty($data['range'])) {
            $range = explode(';', $data['range']);

            if ($data['range'] == "1") {
                $assets->whereBetween('value', [$range[0], $range[1]])->orWhere('value', null);
            } else {
                $assets->whereBetween('value', [$range[0], $range[1]]);
            }
        }

        if ( ! empty($data['category'])) {
            $assets->whereIn('category_id', $data['category']);
        }

        if ( ! empty($data['year'])) {
            $assets->whereIn('year', $data['year']);
        }

        $parts = explode(' ', $this->property('sortOrder'));
        if (count($parts) < 2) {
            array_push($parts, 'desc');
        }
        list($sortField, $sortDirection) = $parts;

        $assets = $assets->orderBy($sortField, $sortDirection);

        if ( ! empty($page)) {
            $assets = $assets->paginate($this->property('assetsPerPage'), $page);
        } else {
            $assets = $assets->paginate($this->property('assetsPerPage'), 1);
        }

        $this->page['assetRecords'] = $assets;
    }

    public function onPageChangeFilter()
    {
        $page = post('page');
        $this->onChangeFilter($page);
    }

    public function user()
    {
        if ( ! Auth::check()) {
            return null;
        }

        return Auth::getUser();
    }

    public function onOpenAssetDetailForm()
    {
        $asset_id = post('asset_id');
        if ( ! empty($asset_id)) {
            $asset                     = Asset::find($asset_id);
            $this->page['assetDetail'] = $asset;
        }
    }

    public function onCreateRecord()
    {
        try {
            $data = post();

            $rules = [
                'activity'             => 'required',
                'asset_id'             => 'required',
                'date_start'           => 'required',
                'expected_return_date' => 'required',
                'task'                 => 'required',
                'asset_conditions'     => 'required',
            ];

            $messages = [

            ];

            $validation = Validator::make($data, $rules, $messages);
            if ($validation->fails()) {
                throw new ValidationException($validation);
            }

            $data['status']  = Record::STATUS_USING;
            $data['user_id'] = $this->user->id;

            if ($data['date_start']) {
                $data['date_start'] = Carbon::createFromFormat('d/m/Y', $data['date_start']);
            }

            if ($data['expected_return_date']) {
                $data['expected_return_date'] = Carbon::createFromFormat('d/m/Y', $data['expected_return_date']);
            }

            $record = new Record();
            $record->fill($data);
            $record->save();
            Flash::success('Record added');

            $asset = Asset::find($data['asset_id']);
            if ( ! empty($asset)) {
                $asset->status = Asset::STATUS_USING;
                $asset->save();
            }

            return Redirect::refresh();

        } catch (\Exception $exception) {
            if (\Illuminate\Support\Facades\Request::ajax()) {
                throw $exception;
            } else {
                Flash::error($exception->getMessage());
            }
        }
    }
}

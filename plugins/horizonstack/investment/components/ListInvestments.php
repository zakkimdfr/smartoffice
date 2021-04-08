<?php namespace Horizonstack\Investment\Components;

use Cms\Classes\ComponentBase;
use Horizonstack\Investment\Models\Investment;
use Horizonstack\Investment\Models\ProjectLevel;
use Horizonstack\Investment\Models\PropertyType;
use Horizonstack\Investment\Models\Scheme;

class ListInvestments extends ComponentBase
{
    public $listInvestments;

    const SORT_OPTION_1 = 'name-asc';
    const SORT_OPTION_2 = 'name-desc';
    const SORT_OPTION_3 = 'created_at-asc';
    const SORT_OPTION_4 = 'created_at-desc';

    public static $sortByOptions = [
        [
            'id'   => self::SORT_OPTION_4,
            'name' => 'Date desc',
        ],
        [
            'id'   => self::SORT_OPTION_3,
            'name' => 'Date asc',
        ],
        [
            'id'   => self::SORT_OPTION_2,
            'name' => 'Name Z to A',
        ],
        [
            'id'   => self::SORT_OPTION_1,
            'name' => 'Name A to Z',
        ],
    ];

    public function componentDetails()
    {
        return [
            'name'        => 'List Investments',
            'description' => 'Render investments on a page',
        ];
    }

    public function defineProperties()
    {
        return [
            'investmentsPerPage' => [
                'title'             => 'Per Page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Numbers only.',
                'default'           => '6',
            ],
        ];
    }

    public function onRun()
    {
        $listInvestments = Investment::with([
            'investment_schemes',
            'photos',
        ])->isActive()->orderBy('created_at',
            'desc')->paginate($this->property('investmentsPerPage'), 1);

        $this->page['listInvestments'] = $listInvestments;

        $this->prepareVars();
    }

    public function prepareVars()
    {
        $this->page['sortOptions']   = self::$sortByOptions;
        $this->page['projectLevels'] = ProjectLevel::isActive()->orderBy('name')->get();
        $this->page['schemes']       = Scheme::with('icon')->isActive()->orderBy('name')->get();
        $this->page['propertyTypes'] = PropertyType::with('icon')->isActive()->orderBy('name')->get();
    }

    public function onChangeFilter($page = null)
    {
        $data = post();

        $listInvestments = Investment::with([
            'investment_schemes',
            'photos',
        ])->isActive();

        //apply filters if filter data available
        if (( ! empty($data['investment_value_from']) or $data['investment_value_from'] == "0") && ! empty($data['investment_value_to'])) {
            $listInvestments->where(function ($query) use ($data) {
                $query->whereBetween('usd',
                    [($data['investment_value_from']) ? $data['investment_value_from'] : 0, $data['investment_value_to'],])->orWhereNull('usd');
            });
        }

        if ( ! empty($data['projectLevels'])) {
            $listInvestments->whereIn('project_level_id', $data['projectLevels']);
        }

        if ( ! empty($data['propertyTypes'])) {
            $listInvestments->whereIn('property_type_id', $data['propertyTypes']);
        }

        if ( ! empty($data['schemes'])) {
            $listInvestments->whereHas('investment_schemes', function ($query) use ($data) {
                $query->whereIn('scheme_id', $data['schemes']);
            });
        }

        //sorting events

        $sortByOptions = [];

        foreach (self::$sortByOptions as $option) {
            $sortByOptions[$option['id']] = $option;
        }

        if ( ! empty($data['sort_by'])) {
            $sort_by = $sortByOptions[$data['sort_by']];
        } else {
            $sort_by = $sortByOptions[self::SORT_OPTION_4];
        }

        $sort_by = $sort_by['id'];

        $parts = explode('-', $sort_by);
        if (count($parts) < 2) {
            array_push($parts, 'desc');
        }
        list($sortField, $sortDirection) = $parts;

        $listInvestments = $listInvestments->orderBy($sortField, $sortDirection);

        if ( ! empty($page)) {
            $listInvestments = $listInvestments->paginate($this->property('investmentsPerPage'), $page);
        } else {
            $listInvestments = $listInvestments->paginate($this->property('investmentsPerPage'), 1);
        }

        $this->page['listInvestments'] = $this->listInvestments = $listInvestments;
    }

    public function onPageChangeFilter()
    {
        $page = post('page');
        $this->onChangeFilter($page);
    }
}

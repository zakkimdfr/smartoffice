<?php namespace Horizonstack\Userextend\Components;

use Cms\Classes\ComponentBase;
use Horizonstack\Guestbook\Models\Event;
use RainLab\User\Models\User;

class ListEmployees extends ComponentBase
{
    public $listEmployees;

    const SORT_OPTION_1 = 'name-asc';
    const SORT_OPTION_2 = 'name-desc';
    const SORT_OPTION_3 = 'date-asc';
    const SORT_OPTION_4 = 'date-desc';

    public static $sortByOptions = [
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
            'name'        => 'List Employees',
            'description' => 'Render all Employees on single page...',
        ];
    }

    public function defineProperties()
    {
        return [
            'employeePerPage' => [
                'title'             => 'Per Page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Numbers only.',
                'default'           => '15',
            ],
        ];
    }

    public function onRun()
    {
        $listEmployees = User::with([
            'avatar',
            'jobLevel',
        ])->orderBy('name',
            'asc')->paginate($this->property('employeePerPage'), 1);

        $this->page['listEmployees'] = $listEmployees;

        $this->page['sortOptions'] = self::$sortByOptions;
    }

    public function prepareVars()
    {
    }

    public function onChangeFilter($page = null)
    {
        $data = post();

        $listEmployees = User::with([
            'avatar',
            'jobLevel',
        ]);

        //sorting events

        $sortByOptions = [];

        foreach (self::$sortByOptions as $option) {
            $sortByOptions[$option['id']] = $option;
        }

        if ( ! empty($data['filter_by'])) {
            $filter_by = $sortByOptions[$data['filter_by']];
        } else {
            $filter_by = $sortByOptions[self::SORT_OPTION_4];
        }

        $filter_by = $filter_by['id'];

        $parts = explode('-', $filter_by);
        if (count($parts) < 2) {
            array_push($parts, 'desc');
        }
        list($sortField, $sortDirection) = $parts;

        $listEmployees = $listEmployees->orderBy($sortField, $sortDirection);

        if ( ! empty($page)) {
            $listEmployees = $listEmployees->paginate($this->property('employeePerPage'), $page);
        } else {
            $listEmployees = $listEmployees->paginate($this->property('employeePerPage'), 1);
        }

        $this->page['listEmployees'] = $this->listEmployees = $listEmployees;
    }

    public function onPageChangeFilter()
    {
        $page = post('page');
        $this->onChangeFilter($page);
    }
}

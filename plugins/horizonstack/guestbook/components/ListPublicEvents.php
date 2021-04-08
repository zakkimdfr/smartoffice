<?php namespace Horizonstack\Guestbook\Components;

use Cms\Classes\ComponentBase;
use Horizonstack\Guestbook\Models\Event;

class ListPublicEvents extends ComponentBase
{
    public $listEvents;

    const SORT_OPTION_1 = 'name-asc';
    const SORT_OPTION_2 = 'name-desc';
    const SORT_OPTION_3 = 'date-asc';
    const SORT_OPTION_4 = 'date-desc';

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
            'name'        => 'List Public Events',
            'description' => 'Render all public Events on single page...',
        ];
    }

    public function defineProperties()
    {
        return [
            'eventsPerPage' => [
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
        $listEvents = Event::with([
            'image',
            'guests',
        ])->withCount('guests')->isPublic()->isActive()->orderBy('date',
            'desc')->paginate($this->property('eventsPerPage'), 1);

        $this->page['listEvents'] = $listEvents;

        $this->page['sortOptions'] = self::$sortByOptions;
    }

    public function prepareVars()
    {
    }

    public function onChangeFilter($page = null)
    {
        $data = post();

        $listEvents = Event::with([
            'image',
            'guests',
        ])->isPublic()->isActive()->withCount('guests');

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

        $listEvents = $listEvents->orderBy($sortField, $sortDirection);

        if ( ! empty($page)) {
            $listEvents = $listEvents->paginate($this->property('eventsPerPage'), $page);
        } else {
            $listEvents = $listEvents->paginate($this->property('eventsPerPage'), 1);
        }

        $this->page['listEvents'] = $this->listEvents = $listEvents;
    }

    public function onPageChangeFilter()
    {
        $page = post('page');
        $this->onChangeFilter($page);
    }
}

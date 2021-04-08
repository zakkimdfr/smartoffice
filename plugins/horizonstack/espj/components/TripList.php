<?php namespace Horizonstack\ESPJ\Components;

use Cms\Classes\ComponentBase;
use Horizonstack\eSPJ\Models\Trip;
use Auth;

class TripList extends ComponentBase
{
    public $listTrips, $user;

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
            'name'        => 'Trip List Component',
            'description' => 'Render all Trips on single page...',
        ];
    }

    public function defineProperties()
    {
        return [
            'tripsPerPage' => [
                'title'             => 'Per Page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Numbers only.',
                'default'           => '15',
            ],
        ];
    }

    public function init()
    {
        $this->user = $this->user();
    }

    public function onRun()
    {
        $this->page['trips'] = $this->prepareVarForTrips();
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

    public function onSearchTrips()
    {
        $options = post();

        /*if (empty($options['searchTerm'])) {
            throw new ValidationException(['searchTerm' => "Enter name or email to search User."]);
        }*/

        if ( ! empty(post('searchTerm'))) {
            $searchTerm = $options['searchTerm'];
        } else {
            $searchTerm = "";
        }

        $trips = $this->prepareVarForTrips($searchTerm, $options);

        $this->page['searchTerm'] = $searchTerm;

        $this->page['trips'] = $trips;
    }

    public function prepareVarForTrips($searchTerm = null, $options = null)
    {
        if (empty($options['sort_by'])) {
            $options['sort_by'] = 'name';
        }

        if (empty($options['sort_direction'])) {
            $options['sort_direction'] = 'asc';
        }

        if (empty($options['page'])) {
            $page = $options['page'] = 1;
        } else {
            $page = $options['page'];
        }

        $this->page['page']           = $page;
        $this->page['sortField']      = $options['sort_by'];
        $this->page['sort_direction'] = $options['sort_direction'];

        return $trips = $this->onLoadTrips($page, $searchTerm, $options);
    }

    public function onLoadTrips($page = null, $name = false, $options = false)
    {
        $listTrips = Trip::with([
            'destinationCity1',
            'participants',
        ])->withCount('participants')
                         ->where(function ($query) {
                             $query->where('user_id', $this->user->id)->orWhereHas('participants',
                                 function ($query) {
                                     $query->where('user_id', $this->user->id);
                                 });
                         });

        if ($name) {
            $listTrips->where('name', 'like', "%".$name."%");
        }

        if ( ! empty($options)) {
            if ($options['sort_by'] == 'department') {
                /*$sortBy = "wwj_accommodation_hotels.date";*/
            } else {
                $sortBy = $options['sort_by'];
            }
            $sortDirection = $options['sort_direction'];
        } else {
            $sortBy        = "name";
            $sortDirection = "asc";
        }

        $listTrips = $listTrips->orderBy($sortBy, $sortDirection);

        if ( ! empty($page)) {
            $listTrips = $listTrips->paginate($this->property('tripsPerPage'), $page);
        } else {
            $listTrips = $listTrips->paginate($this->property('tripsPerPage'), 1);
        }

        return $listTrips;
    }

    public function onPageChange()
    {
        $options = post();
        if ( ! empty(post('searchTerm'))) {
            $searchTerm = $options['searchTerm'];
        }

        if (empty($options['sort_by'])) {
            $options['sort_by'] = 'name';
        }

        if (empty($options['sort_direction'])) {
            $options['sort_direction'] = 'asc';
        }

        if ( ! empty($searchTerm)) {
            $trips                    = $this->prepareVarForTrips($searchTerm, $options);
            $this->page['searchTerm'] = $searchTerm;
        } else {
            $trips = $this->prepareVarForTrips(false, $options);
        }

        $this->page['trips'] = $trips;
    }
}

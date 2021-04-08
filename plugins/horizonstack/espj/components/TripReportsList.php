<?php namespace Horizonstack\ESPJ\Components;

use Cms\Classes\ComponentBase;
use Auth;
use Horizonstack\eSPJ\Models\TripReport;

class TripReportsList extends ComponentBase
{
    public $listTripReports, $user;

    public function componentDetails()
    {
        return [
            'name'        => 'Trip Reports List',
            'description' => 'Render trip reports in table view...',
        ];
    }

    public function defineProperties()
    {
        return [
            'tripReportsPerPage' => [
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
        $this->page['tripReports'] = $this->prepareVarForTripReports();
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

    public function onSearchTripReports()
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

        $trips = $this->prepareVarForTripReports($searchTerm, $options);

        $this->page['searchTerm'] = $searchTerm;

        $this->page['tripReports'] = $trips;
    }

    public function prepareVarForTripReports($searchTerm = null, $options = null)
    {
        if (empty($options['sort_by'])) {
            $options['sort_by'] = 'created_at';
        }

        if (empty($options['sort_direction'])) {
            $options['sort_direction'] = 'desc';
        }

        if (empty($options['page'])) {
            $page = $options['page'] = 1;
        } else {
            $page = $options['page'];
        }

        $this->page['page']           = $page;
        $this->page['sortField']      = $options['sort_by'];
        $this->page['sort_direction'] = $options['sort_direction'];

        return $trips = $this->onLoadTripReports($page, $searchTerm, $options);
    }

    public function onLoadTripReports($page = null, $name = false, $options = false)
    {
        $listTripReports = TripReport::with([
            'user',
            'trip',
            'attnTo',
        ])->where('user_id', $this->user->id);

        if ($name) {
            $listTripReports->where('name', 'like', "%".$name."%");
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

        $listTripReports = $listTripReports->orderBy($sortBy, $sortDirection);

        if ( ! empty($page)) {
            $listTripReports = $listTripReports->paginate($this->property('tripReportsPerPage'), $page);
        } else {
            $listTripReports = $listTripReports->paginate($this->property('tripReportsPerPage'), 1);
        }

        return $listTripReports;
    }

    public function onPageChangeTripReports()
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
            $trips                    = $this->prepareVarForTripReports($searchTerm, $options);
            $this->page['searchTerm'] = $searchTerm;
        } else {
            $trips = $this->prepareVarForTripReports(false, $options);
        }

        $this->page['tripReports'] = $trips;
    }
}

<?php namespace Horizonstack\ESPJ\Components;

use Cms\Classes\ComponentBase;
use Auth;
use Horizonstack\eSPJ\Models\RealityForm;

class TripRealityFormsList extends ComponentBase
{
    public $listTripRealityForms, $user;

    public function componentDetails()
    {
        return [
            'name'        => 'Trip Reality Forms List',
            'description' => 'Render trip Reality Forms in table view...',
        ];
    }

    public function defineProperties()
    {
        return [
            'tripRealityFormsPerPage' => [
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
        $this->page['tripRealityForms'] = $this->prepareVarForTripRealityForms();
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

    public function onSearchTripRealityForms()
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

        $trips = $this->prepareVarForTripRealityForms($searchTerm, $options);

        $this->page['searchTerm'] = $searchTerm;

        $this->page['tripRealityForms'] = $trips;
    }

    public function prepareVarForTripRealityForms($searchTerm = null, $options = null)
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
        $listTripRealityForms = RealityForm::with([
            'user',
            'trip',
        ])->where('user_id', $this->user->id);

        if ($name) {
            $listTripRealityForms->where('name', 'like', "%".$name."%");
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

        $listTripRealityForms = $listTripRealityForms->orderBy($sortBy, $sortDirection);

        if ( ! empty($page)) {
            $listTripRealityForms = $listTripRealityForms->paginate($this->property('tripRealityFormsPerPage'), $page);
        } else {
            $listTripRealityForms = $listTripRealityForms->paginate($this->property('tripRealityFormsPerPage'), 1);
        }

        return $listTripRealityForms;
    }

    public function onPageChangeTripRealityForms()
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
            $trips                    = $this->prepareVarForTripRealityForms($searchTerm, $options);
            $this->page['searchTerm'] = $searchTerm;
        } else {
            $trips = $this->prepareVarForTripRealityForms(false, $options);
        }

        $this->page['tripRealityForms'] = $trips;
    }
}

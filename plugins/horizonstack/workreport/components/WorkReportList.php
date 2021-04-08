<?php namespace Horizonstack\WorkReport\Components;

use Cms\Classes\ComponentBase;
use Horizonstack\Workreport\Models\Report;
use Auth;

class WorkReportList extends ComponentBase
{
    public $user;

    public function componentDetails()
    {
        return [
            'name'        => 'Work Report List',
            'description' => 'Render work reports in table view...',
        ];
    }

    public function defineProperties()
    {
        return [
            'workReportsPerPage' => [
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
        $this->page['workReports'] = $this->prepareVarForWorkReports();
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

        $trips = $this->prepareVarForWorkReports($searchTerm, $options);

        $this->page['searchTerm'] = $searchTerm;

        $this->page['workReports'] = $trips;
    }

    public function prepareVarForWorkReports($searchTerm = null, $options = null)
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

        return $trips = $this->onLoadWorkReports($page, $searchTerm, $options);
    }

    public function onLoadWorkReports($page = null, $name = false, $options = false)
    {
        $listWorkReports = Report::with([
            'user',
            'attnTo',
        ]);

        if ($this->user->user_type == USER_TYPE_EMPLOYEE) {
            $listWorkReports->where('user_id', $this->user->id);
        }

        if ($name) {
            $listWorkReports->where('name', 'like', "%".$name."%");
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

        $listWorkReports = $listWorkReports->orderBy($sortBy, $sortDirection);

        if ( ! empty($page)) {
            $listWorkReports = $listWorkReports->paginate($this->property('workReportsPerPage'), $page);
        } else {
            $listWorkReports = $listWorkReports->paginate($this->property('workReportsPerPage'), 1);
        }

        return $listWorkReports;
    }

    public function onPageChangeWorkReports()
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
            $listWorkReports          = $this->prepareVarForWorkReports($searchTerm, $options);
            $this->page['searchTerm'] = $searchTerm;
        } else {
            $listWorkReports = $this->prepareVarForWorkReports(false, $options);
        }

        $this->page['workReports'] = $listWorkReports;
    }
}

<?php namespace Horizonstack\Guestbook\Components;

use Cms\Classes\ComponentBase;
use Horizonstack\eSPJ\Models\Trip;
use Horizonstack\Guestbook\Models\City;
use Horizonstack\Guestbook\Models\Event;
use Horizonstack\Guestbook\Models\Guest;
use Horizonstack\Guestbook\Models\Job;
use DB;
use Horizonstack\Guestbook\Models\Organization;

class GuestbookStats extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'GuestBook Stats',
            'description' => 'Render all statistics about Guest book plugin.',
        ];
    }

    public function defineProperties()
    {
        return [
            'guestsPerPage' => [
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
        $this->loadBoxesData();
        $this->loadGuestJobsChartData();
        $this->loadGuestBarChartData();
        $this->loadTopCitiesData();
        $this->loadTopOrganisationsData();

        $this->page['guests'] = $this->prepareVarForGuests();
    }

    public function loadGuestJobsChartData()
    {
        $guestJobChartData   = [];
        $guestJobChartLabels = [];
        $guestJobChartSeries = [];

        $guestJobs = Job::withCount(['guests'])->isActive()->get();

        foreach ($guestJobs as $guestJob) {
            $guestJobChartLabels[] = $guestJob->name;
            $guestJobChartSeries[] = $guestJob->guests_count;
        }

        $guestJobChartData['labels']      = $guestJobChartLabels;
        $guestJobChartData['series']      = $guestJobChartSeries;
        $this->page['guestJobsChartData'] = $guestJobChartData;
    }

    public function loadBoxesData()
    {
        $totalGuests               = Guest::count();
        $this->page['totalGuests'] = $totalGuests;

        $totalEvents               = Event::count();
        $this->page['totalEvents'] = $totalEvents;

        if ($totalEvents > 0) {
            $this->page['avgGuestPerEvent'] = ($totalGuests / $totalEvents);
        }
    }

    public function loadGuestBarChartData()
    {
        $months = [
            'Januari'   => 1,
            'Februari'  => 2,
            'Maret'     => 3,
            'April'     => 4,
            'Mei'       => 5,
            'Juni'      => 6,
            'Juli'      => 7,
            'Agustus'   => 8,
            'September' => 9,
            'Oktober'   => 10,
            'November'  => 11,
            'Desember'  => 12,
        ];

        $guestsBarChartData = [];
        /* Bar chart */
        if (count($months)) {
            $barChartLabels = [];
            $barChartSeries = [];
            foreach ($months as $label => $month) {
                if ( ! empty($month)) {
                    $guests = Guest::where(DB::raw('MONTH(created_at)'), '=', $month)->count();
                }

                $barChartLabels[] = $label;
                $barChartSeries[] = $guests;
            }

            $guestsBarChartData['labels'] = $barChartLabels;
            $guestsBarChartData['series'] = $barChartSeries;
        }

        $this->page['guestsBarChart'] = $guestsBarChartData;
    }

    public function loadTopCitiesData()
    {
        $topCities = City::withCount('guests')->get()->sortByDesc(function ($city, $key) {
            return $city->guests_count;
        })->take(10);

        $this->page['topCities'] = $topCities;
    }

    public function loadTopOrganisationsData()
    {
        $topOrganisations = Organization::withCount('guests')->get()->sortByDesc(function ($organization, $key) {
            return $organization->guests_count;
        })->take(10);

        $this->page['topOrganisations'] = $topOrganisations;
    }

    public function prepareVarForGuests($searchTerm = null, $options = null)
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

        return $guests = $this->onLoadGuests($page, $searchTerm, $options);
    }

    public function onLoadGuests($page = null, $name = false, $options = false)
    {
        $listGuests = Guest::with([
            'image',
            'job',
            'guestOrganization',
            'guestCity',
        ]);

        if ($name) {
            $listGuests->where('name', 'like', "%".$name."%");
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

        $listGuests = $listGuests->orderBy($sortBy, $sortDirection);

        if ( ! empty($page)) {
            $listGuests = $listGuests->paginate($this->property('guestsPerPage'), $page);
        } else {
            $listGuests = $listGuests->paginate($this->property('guestsPerPage'), 1);
        }

        return $listGuests;
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
            $guests                   = $this->prepareVarForGuests($searchTerm, $options);
            $this->page['searchTerm'] = $searchTerm;
        } else {
            $guests = $this->prepareVarForGuests(false, $options);
        }

        $this->page['guests'] = $guests;
    }

    public function onSearchGuests()
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

        $guests = $this->prepareVarForGuests($searchTerm, $options);

        $this->page['searchTerm'] = $searchTerm;

        $this->page['guests'] = $guests;

    }

    public function onOpenGuestDetail()
    {
        $guestId = post('guestId');

        if ( ! empty($guestId)) {
            $guest = Guest::with(['image', 'guestOrganization', 'guestOrganization', 'job'])->find($guestId);

            if ( ! empty($guest)) {
                $this->page['guestDetail'] = $guest;
            }
        }

    }
}

<?php namespace Horizonstack\Userextend\Components;

use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use Horizonstack\Asset\Models\Asset;
use Horizonstack\Asset\Models\Record;
use Horizonstack\eSPJ\Models\Trip;
use Horizonstack\Guestbook\Models\Event;
use Horizonstack\Guestbook\Models\Guest;
use Auth;

class DashboardStats extends ComponentBase
{
    public $user;

    public function componentDetails()
    {
        return [
            'name'        => 'Dashboard Stats',
            'description' => 'Render all dashboard data.',
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
        $this->loadFirstBoxData();
        $this->loadSecondBoxData();
        $this->loadPerjalanData();
        $this->loadAssetsStats();
        $this->loadGuestBookStats();
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

    public function loadFirstBoxData()
    {
        $totalGuests = Guest::count();

        $this->page['totalGuests'] = $totalGuests;
    }

    public function loadSecondBoxData()
    {
        $totalTrips = Trip::count();

        $this->page['totalTrips'] = $totalTrips;
    }

    public function loadPerjalanData()
    {
        $latestTrips               = Trip::with(['user', 'destinationCity1'])->orderBy('created_at',
            'desc')->limit(7)->get();
        $this->page['latestTrips'] = $latestTrips;
    }

    public function loadAssetsStats()
    {
        $totalAssets               = Asset::count();
        $this->page['totalAssets'] = $totalAssets;

        $currentlyUsedAssetRecords               = Record::where('user_id', $this->user->id)->where('status',
            Asset::STATUS_USING)->count();
        $this->page['currentlyUsedAssetRecords'] = $currentlyUsedAssetRecords;

        $totalUsedAssetRecords               = Record::where('user_id', $this->user->id)->count();
        $this->page['totalUsedAssetRecords'] = $totalUsedAssetRecords;

        $userPercentageAssets               = ($totalUsedAssetRecords / $totalAssets) * 100;
        $this->page['userPercentageAssets'] = $userPercentageAssets;
    }

    public function loadGuestBookStats()
    {
        $totalEvents = Event::isActive()->count();
        $this->page['totalEvents'] = $totalEvents;

        $totalEventGuests = Guest::count();
        $this->page['totalEventGuests'] = $totalEventGuests;

        $monthlyEventsChartDataLabels = [];
        $monthlyEventsChartDataSeries = [];
        $monthlyEventsChartData       = [];

        $months = [
            [
                'value' => 1,
                'label' => 'Jan',
            ],
            [
                'value' => 2,
                'label' => 'Feb',
            ],
            [
                'value' => 3,
                'label' => 'Mar',
            ],
            [
                'value' => 4,
                'label' => 'Apr',
            ],
            [
                'value' => 5,
                'label' => 'Mei',
            ],
            [
                'value' => 6,
                'label' => 'Jun',
            ],
            [
                'value' => 7,
                'label' => 'Jul',
            ],
            [
                'value' => 8,
                'label' => 'Agu',
            ],
            [
                'value' => 9,
                'label' => 'Sep',
            ],
            [
                'value' => 10,
                'label' => 'Okt',
            ],
            [
                'value' => 11,
                'label' => 'Nov',
            ],
            [
                'value' => 12,
                'label' => 'Des',
            ],
        ];

        foreach ($months as $month) {
            $eventData = Event::isActive()->whereMonth('created_at', $month['value'])->whereYear('created_at',
                Carbon::now()->year)->count();

            $monthlyEventsChartDataLabels[] = (string)$month['label'];
            $monthlyEventsChartDataSeries[] = $eventData;
        }
        $monthlyEventsChartData['labels']     = $monthlyEventsChartDataLabels;
        $monthlyEventsChartData['series']     = $monthlyEventsChartDataSeries;
        $this->page['monthlyEventsChartData'] = $monthlyEventsChartData;
    }
}

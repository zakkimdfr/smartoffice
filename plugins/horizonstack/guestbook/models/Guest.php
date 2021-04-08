<?php namespace Horizonstack\Guestbook\Models;

use Model;
use October\Rain\Database\Traits\Sluggable;

/**
 * Model
 */
class Guest extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use Sluggable;

    const STATUS_INVITED = 1;
    const STATUS_REPRESENT = 2;
    const STATUS_CO_INVITED = 3;

    const INVITATION_CONFIRMATION_PENDING = 1;
    const INVITATION_CONFIRMATION_ACCEPTED = 2;
    const INVITATION_CONFIRMATION_REJECTED = 3;



    protected $slugs = ['slug' => 'name'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'horizonstack_guestbook_guests';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'            => 'required',
        'phone'           => 'required',
        'email'           => 'required|email',
        'job_id'          => 'required',
        'organization_id' => 'required',
        'city_id'         => 'required',
        'event_id'        => 'required',
    ];

    public $attachOne = [
        'image' => ['System\Models\File', 'delete' => true],
    ];

    public $belongsTo = [
        'job'               => Job::class,
        'event'             => Event::class,
        'guestOrganization' => [
            Organization::class,
            'key' => 'organization_id',
        ],
        'guestCity'         => [
            City::class,
            'key' => 'city_id',
        ],
    ];

    public $fillable = [
        'name',
        'slug',
        'phone',
        'job_id',
        'organization',
        'email',
        'city',
        'event_id',
        'status',
        'city_id',
        'organization_id',
        'invitation_code',
        'invitation_confirmation',
        'invitation_responded_at',
        'job_title',
        'is_souvenirs',
    ];

    public function getOrganizationIdOptions()
    {
        $organizationsList = [null => "Select Organization"];

        $organizations = Organization::isActive()->orderBy('name');

        if ($this->event_id) {
            $organizations->whereHas('events', function ($query) {
                $query->where('id', $this->event_id);
            });
        }

        $organizations = $organizations->select('id', 'name')->get();

        foreach ($organizations as $organization) {
            $organizationsList[$organization->id] = $organization->name;
        }

        return $organizationsList;
    }

    public function getCityIdOptions()
    {
        $citiesList = [null => "Select City"];

        $cities = City::isActive()->orderBy('name')->select('id', 'name')->get();

        foreach ($cities as $city) {
            $citiesList[$city->id] = $city->name;
        }

        return $citiesList;
    }

    public function getStatusOptions()
    {
        return [
            self::STATUS_INVITED    => 'Terundang',
            self::STATUS_REPRESENT  => 'Mewakili',
            self::STATUS_CO_INVITED => 'Mendampingi',
        ];
    }

    public function getInvitationConfirmationOptions()
    {
        return [
            self::INVITATION_CONFIRMATION_PENDING    => 'Pending',
            self::INVITATION_CONFIRMATION_ACCEPTED  => 'Accepted',
            self::INVITATION_CONFIRMATION_REJECTED => 'Rejected',
        ];
    }

    public function getJobIdOptions()
    {
        $jobsList = [null => "Select Job"];

        $jobs = Job::isActive()->orderBy('name')->select('id', 'name')->get();

        foreach ($jobs as $job) {
            $jobsList[$job->id] = $job->name;
        }

        return $jobsList;
    }

    public function getEventIdOptions()
    {
        $eventsList = [null => "Select Event"];

        $events = Event::isActive()->orderBy('name')->select('id', 'name')->get();

        foreach ($events as $event) {
            $eventsList[$event->id] = $event->name;
        }

        return $eventsList;
    }
}

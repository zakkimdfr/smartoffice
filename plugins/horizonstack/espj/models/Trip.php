<?php namespace Horizonstack\eSPJ\Models;

use Carbon\Carbon;
use Horizonstack\Guestbook\Models\City;
use Model;
use October\Rain\Database\Traits\Sluggable;
use RainLab\User\Models\User;

/**
 * Model
 */
class Trip extends Model
{
    use Sluggable;
    use \October\Rain\Database\Traits\Validation;

    protected $slugs = ['slug' => 'name'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'horizonstack_espj_trips';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'               => 'required',
        'user_id'            => 'required',
        'budget_name'        => 'required',
        'mission'            => 'required',
        'start_at'           => 'required',
        'return_at'          => 'required',
        'transportation_id'  => 'required',
        'signature_id'       => 'required',
        'origin_city_id'     => 'required',
        'destination_city_1' => 'required',
        'return_city_id'     => 'required',
    ];

    public $fillable = [
        'name',
        'slug',
        'budget_name',
        'mission',
        'transportation_id',
        'start_at',
        'return_at',
        'origin_city_id',
        'destination_city_1',
        'destination_city_2',
        'return_city_id',
        'signature_id',
        'remarks',
        'user_id',
        'signature_date',
        'days',
        'nights',
    ];

    public $belongsTo = [
        'user'             => User::class,
        'signature'        => Signature::class,
        'transportation'   => Transportation::class,
        'origin_city'      => [
            City::class,
            'key'   => 'origin_city_id',
            'order' => 'name',
        ],
        'return_city'      => [
            City::class,
            'key'   => 'return_city_id',
            'order' => 'name',
        ],
        'destinationCity1' => [
            City::class,
            'key'   => 'destination_city_1',
            'order' => 'name',
        ],
    ];

    public $belongsToMany = [
        'destinations' => [
            City::class,
            'table' => 'horizonstack_espj_trip_destinations',
        ],
        'participants' => [
            User::class,
            'table' => 'horizonstack_espj_trip_participants',
        ],
    ];

    public $hasMany = [
        'reports'      => [
            TripReport::class,
            'table'  => 'horizonstack_espj_trip_reports',
            'key'    => 'trip_id',
            'delete' => true,
        ],
        'realityForms' => [
            RealityForm::class,
            'table'  => 'horizonstack_espj_reality_forms',
            'key'    => 'trip_id',
            'delete' => true,
        ],
    ];

    public function getUserIdOptions()
    {
        $usersList = [null => "Select User"];

        $users = User::orderBy('name')->select('id', 'name')->get();

        foreach ($users as $user) {
            $usersList[$user->id] = $user->name;
        }

        return $usersList;
    }

    public function getTransportationIdOptions()
    {
        $transportationList = [null => "Select Transportation"];

        $transportations = Transportation::orderBy('name')->select('id', 'name')->get();

        foreach ($transportations as $transportation) {
            $transportationList[$transportation->id] = $transportation->name;
        }

        return $transportationList;
    }

    public function getSignatureIdOptions()
    {
        $signaturesList = [null => "Select Signature"];

        $signatures = Signature::orderBy('name')->select('id', 'name')->get();

        foreach ($signatures as $signature) {
            $signaturesList[$signature->id] = $signature->name;
        }

        return $signaturesList;
    }

    public function getDestinationCity1Options()
    {
        $citiesList = [null => "Select Destination City 1"];

        $citites = City::orderBy('name')->select('id', 'name')->get();

        foreach ($citites as $city) {
            $citiesList[$city->id] = $city->name;
        }

        return $citiesList;
    }

    public function getDestinationCity2Options()
    {
        $citiesList = [null => "Select Destination City 2"];

        $citites = City::orderBy('name')->select('id', 'name')->get();

        foreach ($citites as $city) {
            $citiesList[$city->id] = $city->name;
        }

        return $citiesList;
    }

    public function beforeSave()
    {
        if (empty($this->destination_city_2)) {
            $this->destination_city_2 = null;
        }

        if ( ! empty($this->start_at) && ! empty($this->return_at)) {
            $starDate = Carbon::parse($this->start_at);
            $endDate  = Carbon::parse($this->return_at);

            if ($starDate && $endDate) {
                $diffDays       = $endDate->diffInDays($starDate);
                $this['days']   = $diffDays + 1;
                $this['nights'] = $diffDays;
            }
        }
    }
}

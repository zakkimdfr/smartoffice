<?php namespace Horizonstack\Investment\Models;

use Horizonstack\Guestbook\Models\City;
use Horizonstack\Location\Models\District;
use Horizonstack\Location\Models\Village;
use Model;
use October\Rain\Database\Traits\Sluggable;
use System\Models\File;

/**
 * Model
 */
class Investment extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use Sluggable;

    protected $slugs = ['slug' => 'name'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'horizonstack_investment_investments';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'        => 'required',
        'agent_name'  => 'required',
        'agent_phone' => 'required',
        'agent_email' => 'required|email',
        'city_id'     => 'required',
        'district_id' => 'required',
        'village_id'  => 'required',
        'address'     => 'required',
        'latitude'    => 'required',
        'longitude'   => 'required',
        'photos'      => 'required',
    ];

    public $fillable = [
        'name',
        'slug',
        'agent_name',
        'agent_phone',
        'agent_email',
        'city_id',
        'district_id',
        'village_id',
        'address',
        'latitude',
        'longitude',
        'ownership_id',
        'project_level_id',
        'organization_id',
        'organization_name',
        'investment_value',
        'objectives',
        'is_active',
        'property_type_id',
        'description',
        'target_operation',
        'land_size',
        'land_allocation',
        'irr',
        'npv',
        'bcr',
        'payback_period',
        'return_of_investment',
        'break_event_point',
        'profitability_index',
        'remarks',
        'period_of_development',
        'human_resources',
        'incentives',
        'office_address',
        'usd',
    ];

    public $belongsTo = [
        'city'         => City::class,
        'ownership'    => Ownership::class,
        'organization' => InvestmentOrganization::class,
        'projectLevel' => ProjectLevel::class,
        'village'      => Village::class,
        'district'     => District::class,
        'propertyType' => PropertyType::class,
    ];

    public $attachMany = [
        'photos' => [File::class, 'delete' => true],
    ];

    public $hasMany = [
        'policyRegulations' => [PolicyRegulation::class, 'delete' => true],
        'interests'         => [InvestmentInterest::class, 'delete' => true],
    ];

    public $attachOne = [
        'ded_document'               => [File::class, 'delete' => true],
        'amdal_document'             => [File::class, 'delete' => true],
        'amdas_document'             => [File::class, 'delete' => true],
        'feasibility_study_document' => [File::class, 'delete' => true],
    ];

    public $belongsToMany = [
        'investment_schemes' => [
            Scheme::class,
            'table'      => 'horizonstack_investment_investment_scheme',
            'timestamps' => true,
        ],
        'infrastructures'    => [
            Infrastructure::class,
            'table'      => 'horizonstack_investment_investment_infrastructure',
            'timestamps' => true,
        ],
    ];

    public function beforeSave()
    {
        if (empty($this->ownership_id)) {
            $this->ownership_id = null;
        }

        if (empty($this->project_level_id)) {
            $this->project_level_id = null;
        }

        if (empty($this->organization_id)) {
            $this->organization_id = null;
        }
    }

    public function getOwnershipIdOptions()
    {
        $ownershipOptions = [null => "Select Ownership"];

        $ownerships = Ownership::isActive()->orderBy('name')->select('id', 'name')->get();

        foreach ($ownerships as $ownership) {
            $ownershipOptions[$ownership->id] = $ownership->name;
        }

        return $ownershipOptions;
    }

    public function getOrganizationIdOptions()
    {
        $organizationsList = [null => "Select Organization"];

        $organizations = InvestmentOrganization::isActive()->orderBy('name')->select('id', 'name')->get();

        foreach ($organizations as $organization) {
            $organizationsList[$organization->id] = $organization->name;
        }

        return $organizationsList;
    }

    public function getProjectLevelIdOptions()
    {
        $projectLevelList = [null => "Select Project Level"];

        $projectLevels = ProjectLevel::isActive()->orderBy('name')->select('id', 'name')->get();

        foreach ($projectLevels as $projectLevel) {
            $projectLevelList[$projectLevel->id] = $projectLevel->name;
        }

        return $projectLevelList;
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

    public function getDistrictIdOptions()
    {
        $city = $this->city_id;
        if ( ! empty($city)) {
            $districtsList = [null => "Select District"];

            $districts = District::where('city_id', $city)->orderBy('name')->select('id', 'name')->get();

            foreach ($districts as $district) {
                $districtsList[$district->id] = $district->name;
            }
        } else {
            $districtsList[''] = "Select City First";
        }

        return $districtsList;
    }

    public function getVillageIdOptions()
    {
        $city     = $this->city_id;
        $district = $this->district_id;
        if ( ! empty($district) && ! empty($city)) {
            $villageList = [null => "Select Village"];

            $villages = Village::where('district_id', $district)->orderBy('name')->select('id', 'name')->get();

            foreach ($villages as $village) {
                $villageList[$village->id] = $village->name;
            }
        } else {
            $villageList[''] = "Select District First";
        }

        return $villageList;
    }

    public function scopeIsActive($query)
    {
        return $query->where('is_active', true);
    }
}

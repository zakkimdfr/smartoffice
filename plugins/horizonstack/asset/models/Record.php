<?php namespace Horizonstack\Asset\Models;

use Model;
use October\Rain\Database\Traits\Sluggable;
use RainLab\User\Models\User;

/**
 * Model
 */
class Record extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use Sluggable;

    protected $slugs = ['slug' => 'activity'];

    const STATUS_USING = '1';
    const STATUS_RETURNED = '2';

    const MAIN_TASK = '1';
    const SIDE_TASK = '2';

    /**
     * @var string The database table used by the model.
     */
    public $table = 'horizonstack_asset_records';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'activity'             => 'required',
        'asset_id'             => 'required',
        'status'               => 'required',
        'date_start'           => 'required',
        'expected_return_date' => 'required',
        'user_id'              => 'required',
        'task'                 => 'required',
        'asset_conditions'     => 'required',
    ];

    public $fillable = [
        'activity',
        'slug',
        'asset_id',
        'status',
        'date_start',
        'date_end',
        'user_id',
        'task',
        'asset_conditions',
        'expected_return_date',
    ];

    public $belongsTo = [
        'asset' => Asset::class,
        'user'  => User::class,
    ];

    /**
     * The attributes on which the post list can be ordered
     * @var array
     */
    public static $allowedSortingOptions = array(
        'name asc'        => 'Name (ascending)',
        'name desc'       => 'Name (descending)',
        'created_at asc'  => 'Created (ascending)',
        'created_at desc' => 'Created (descending)',
        'updated_at asc'  => 'Updated (ascending)',
        'updated_at desc' => 'Updated (descending)',
    );

    public function getAssetIdOptions()
    {
        $assetsOptions = [null => "Select Asset"];

        $assets = Asset::isActive()->orderBy('name')->select('id', 'name')->get();

        foreach ($assets as $asset) {
            $assetsOptions[$asset->id] = $asset->name;
        }

        return $assetsOptions;
    }

    public function getStatusOptions()
    {
        return [
            null                  => 'Select Status',
            self::STATUS_USING    => 'Using',
            self::STATUS_RETURNED => 'Returned',
        ];
    }

    public function getTaskOptions()
    {
        return [
            null            => 'Select Task',
            self::MAIN_TASK => 'Tugas Utama',
            self::SIDE_TASK => 'Tugas Tambahan',
        ];
    }

    public function getUserIdOptions()
    {
        $usersOptions = [null => "Select User"];

        $users = User::orderBy('name')->select('id', 'name')->get();

        foreach ($users as $user) {
            $usersOptions[$user->id] = $user->name." ".$user->surname;
        }

        return $usersOptions;
    }
}

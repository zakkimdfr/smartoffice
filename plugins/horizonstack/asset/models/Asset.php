<?php namespace Horizonstack\Asset\Models;

use Carbon\Carbon;
use Model;
use System\Models\File;

/**
 * Model
 */
class Asset extends Model
{
    use \October\Rain\Database\Traits\Validation;

    const STATUS_USING = '1';
    const STATUS_RETURNED = '2';

    /**
     * @var string The database table used by the model.
     */
    public $table = 'horizonstack_asset_assets';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'        => ' required',
        'category_id' => ' required',
        'type_id'     => ' required',
        'code'        => ' required',
        'year'        => ' required',
        'value'       => ' required',
    ];

    public $fillable = [
        'name',
        'slug',
        'code',
        'year',
        'category_id',
        'type_id',
        'value',
        'status',
        'remarks',
        'is_active',
    ];

    public $attachMany = [
        'photos' => [File::class, 'delete' => true],
    ];

    public $belongsTo = [
        'category' => AssetCategory::class,
        'type'     => AssetType::class,
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

    public function getCategoryIdOptions()
    {
        $assetCategoryOptions = [null => "Select Category"];

        $assetCategories = AssetCategory::isActive()->orderBy('name')->select('id', 'name')->get();

        foreach ($assetCategories as $assetCategory) {
            $assetCategoryOptions[$assetCategory->id] = $assetCategory->name;
        }

        return $assetCategoryOptions;
    }

    public function getTypeIdOptions()
    {
        $category = $this->category_id;
        if ( ! empty($category)) {
            $types     = AssetType::where('category_id', $category)->orderBy('name')->get();
            $typesList = array_pluck($types, 'name', 'id');

            return $typesList;
        } else {
            $typesList = [null => "Select Category First"];

            return $typesList;
        }
    }

    public function scopeIsActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getYearOptions()
    {
        $years = [null => "Select Year"];

        for ($n = 2010; $n <= Carbon::now()->year; $n++) {
            $years[$n] = $n;
        }

        return $years;
    }

    public function getStatusOptions()
    {
        return [
            null                  => 'Select Status',
            self::STATUS_USING    => 'Using',
            self::STATUS_RETURNED => 'Returned',
        ];
    }
}

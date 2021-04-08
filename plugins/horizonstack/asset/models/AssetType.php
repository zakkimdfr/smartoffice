<?php namespace Horizonstack\Asset\Models;

use Model;

/**
 * Model
 */
class AssetType extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'horizonstack_asset_types';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'        => 'required',
        'category_id' => 'required',
    ];

    public $belongsTo = ['category' => AssetCategory::class];

    public function getCategoryIdOptions()
    {
        $assetCategoryOptions = [null => "Select Category"];

        $assetCategories = AssetCategory::isActive()->orderBy('name')->select('id', 'name')->get();

        foreach ($assetCategories as $assetCategory) {
            $assetCategoryOptions[$assetCategory->id] = $assetCategory->name;
        }

        return $assetCategoryOptions;
    }

    public function scopeIsActive($query)
    {
        return $query->where('is_active', true);
    }
}

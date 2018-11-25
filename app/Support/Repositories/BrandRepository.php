<?php
/**
 * Brand repository.
 */

namespace App\Support\Repositories;


use App\Models\AttributeValue;
use Illuminate\Database\Eloquent\Model;

class BrandRepository
{
    /**
     * Get brand attribute value by related vendor brand model with given own vendor brand's id.
     *
     * @param int $vendorId
     * @param int $vendorAttributeValueId
     * @return AttributeValue|Model|null
     */
    public function getBrandValueByVendorId(int $vendorId, int $vendorAttributeValueId)
    {
        return AttributeValue::query()->whereHas('vendorAttributeValues', function ($query) use ($vendorId, $vendorAttributeValueId) {
            $query->where([
                ['vendors_id', '=', $vendorId],
                ['vendor_attribute_value_id', '=', $vendorAttributeValueId],
            ]);
        })
            ->first();
    }

    /**
     * Get an existing brand attribute value with fields equal to the inserting.
     *
     * @param array $vendorBrandValueData
     * @return AttributeValue|Model|null
     */
    public function getBrandValueByModelData(array $vendorBrandValueData)
    {
        $searchDoubleKeys = config('shop.search_double_by.brand_attribute_value');

        return AttributeValue::query()->where(function ($query) use ($searchDoubleKeys, $vendorBrandValueData) {
            foreach ($searchDoubleKeys as $field) {
                $query->orWhere($field, $vendorBrandValueData[$field]);
            }
        })
            ->first();
    }
}
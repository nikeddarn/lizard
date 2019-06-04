<?php
/**
 * Attribute repository.
 */

namespace App\Support\Repositories;


use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Eloquent\Model;

class AttributeRepository
{
    /**
     * Get attribute by related vendor attribute model with given own vendor attribute's id.
     *
     * @param int $vendorId
     * @param int $vendorAttributeId
     * @return Attribute|Model|null
     */
    public function getAttributeByVendorId(int $vendorId, int $vendorAttributeId)
    {
        return Attribute::query()->whereHas('vendorAttributes', function ($query) use ($vendorId, $vendorAttributeId) {
            $query->where([
                ['vendors_id', '=', $vendorId],
                ['vendor_attribute_id', '=', $vendorAttributeId],
            ]);
        })
            ->first();
    }

    /**
     * Get an existing attribute with fields equal to the inserting.
     *
     * @param array $vendorAttributeData
     * @return Attribute|Model|null
     */
    public function getAttributeByModelData(array $vendorAttributeData)
    {
        /**
         * ToDo Enhance parser. Store attribute in table to merge attributes by manager.
         */

        $searchDoubleKeys = config('vendor.search_double_by.attribute');

        return Attribute::query()->where(function ($query) use ($searchDoubleKeys, $vendorAttributeData) {
            foreach ($searchDoubleKeys as $field) {
                $query->orWhere($field, $vendorAttributeData[$field]);
            }
        })
            ->first();
    }

    /**
     * Get attribute value by related vendor product model with given own vendor product's is.
     *
     * @param int $vendorId
     * @param int $vendorAttributeValueId
     * @return AttributeValue|Model|null
     */
    public function getAttributeValueByVendorId(int $vendorId, int $vendorAttributeValueId)
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
     * Get an existing attribute value with fields equal to the inserting.
     *
     * @param array $vendorAttributeValueData
     * @return AttributeValue|Model|null
     */
    public function getAttributeValueByModelData(array $vendorAttributeValueData)
    {
        /**
         * ToDo Enhance parser. Store attribute value in table to merge by manager.
         */

        $searchDoubleKeys = config('vendor.search_double_by.attribute_value');

        return AttributeValue::query()
            ->where('attributes_id', $vendorAttributeValueData['attributes_id'])
            ->where(function ($query) use ($searchDoubleKeys, $vendorAttributeValueData) {
            foreach ($searchDoubleKeys as $field) {
                $query->orWhere($field, $vendorAttributeValueData[$field]);
            }
        })
            ->first();
    }
}

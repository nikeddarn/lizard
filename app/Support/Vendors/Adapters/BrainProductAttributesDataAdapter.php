<?php
/**
 * Create product attributes data.
 */

namespace App\Support\Vendors\Adapters;


use App\Contracts\Vendor\VendorInterface;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Support\Repositories\AttributeRepository;
use Illuminate\Support\Str;
use stdClass;

class BrainProductAttributesDataAdapter
{
    /**
     * @var int
     */
    const VENDOR_ID = VendorInterface::BRAIN;
    /**
     * @var Attribute
     */
    private $attribute;
    /**
     * @var AttributeRepository
     */
    private $repository;
    /**
     * @var AttributeValue
     */
    private $attributeValue;


    /**
     * BrainProductAttributesDataAdapter constructor.
     * @param Attribute $attribute
     * @param AttributeValue $attributeValue
     * @param AttributeRepository $repository
     */
    public function __construct(Attribute $attribute, AttributeValue $attributeValue, AttributeRepository $repository)
    {
        $this->attribute = $attribute;
        $this->repository = $repository;
        $this->attributeValue = $attributeValue;
    }

    /**
     * Prepare product attributes data.
     *
     * @param array $productAttributesDataRu
     * @param array $productAttributesDataUa
     * @return array
     */
    public function prepareProductAttributesData(array $productAttributesDataRu, array $productAttributesDataUa)
    {
        $attributesData = [];

        foreach (array_keys($productAttributesDataRu) as $key) {
            $attributeId = $this->getAttribute($productAttributesDataRu[$key], $productAttributesDataUa[$key])->id;
            $attributeValueId = $this->getAttributeValue($attributeId, $productAttributesDataRu[$key], $productAttributesDataUa[$key])->id;

            $attributesData[$attributeValueId] = [
                'attributes_id' => $attributeId,
            ];
        }

        return $attributesData;
    }

    /**
     * Get or create attribute by vendor's data.
     *
     * @param stdClass $attributeDataRu
     * @param stdClass $attributeDataUa
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    private function getAttribute(stdClass $attributeDataRu, stdClass $attributeDataUa)
    {
        // own vendor's attribute id
        $vendorAttributeId = $attributeDataRu->OptionID;

        // retrieve existing attribute by vendor id
        $attribute = $this->repository->getAttributeByVendorId(self::VENDOR_ID, $vendorAttributeId);

        if (!$attribute) {

            // prepare attribute data
            $attributeData = [
                'name_ru' => $attributeDataRu->OptionName,
                'name_uk' => $attributeDataUa->OptionName,
                'multiply_product_values' => 1,
            ];

            // try to retrieve existing attribute of another vendor by data
            $attribute = $this->repository->getAttributeByModelData($attributeData);

            // create new attribute
            if (!$attribute) {
                $attribute = $this->attribute->newQuery()->create($attributeData);
            }

            // attach attribute to vendor
            $attribute->vendors()->attach(self::VENDOR_ID, [
                'vendor_attribute_id' => $vendorAttributeId,
            ]);
        }

        return $attribute;
    }

    /**
     * Get or create attribute value by vendor's data.
     *
     * @param int $attributeId
     * @param stdClass $attributeDataRu
     * @param stdClass $attributeDataUa
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    private function getAttributeValue(int $attributeId, stdClass $attributeDataRu, stdClass $attributeDataUa)
    {
        // own vendor's attribute id
        $vendorAttributeValueId = $attributeDataRu->ValueID;

        // retrieve existing attribute by vendor id
        $attributeValue = $this->repository->getAttributeValueByVendorId(self::VENDOR_ID, $vendorAttributeValueId);

        if (!$attributeValue) {

            // prepare attribute data
            $attributeValueData = [
                'attributes_id' => $attributeId,
                'value_ru' => $attributeDataRu->ValueName,
                'value_uk' => $attributeDataUa->ValueName,
                'url' => Str::slug($attributeDataRu->ValueName),
            ];

            // try to retrieve existing attribute of another vendor by data
            $attributeValue = $this->repository->getAttributeValueByModelData($attributeValueData);

            // create new attribute
            if (!$attributeValue) {
                $attributeValue = $this->attributeValue->newQuery()->create($attributeValueData);
            }

            // attach attribute to vendor
            $attributeValue->vendors()->attach(self::VENDOR_ID, [
                'vendor_attribute_value_id' => $vendorAttributeValueId,
            ]);
        }

        return $attributeValue;
    }
}

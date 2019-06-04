<?php
/**
 * Create product attributes data.
 */

namespace App\Support\Vendors\Adapters\Brain;


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
            $attribute = $this->getAttribute($productAttributesDataRu[$key], $productAttributesDataUa[$key]);

            if (!$attribute){
                continue;
            }

            $attributeValue = $this->getAttributeValue($attribute->id, $productAttributesDataRu[$key], $productAttributesDataUa[$key]);

            if (!$attributeValue){
                continue;
            }

            $attributesData[$attributeValue->id] = [
                'attributes_id' => $attribute->id,
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
            $attributeUrl = Str::slug($attributeDataRu->ValueName);

            // not insert able attribute value
            if (!$attributeUrl){
                return null;
            }

            // prepare attribute data
            $attributeValueData = [
                'attributes_id' => $attributeId,
                'value_ru' => $attributeDataRu->ValueName,
                'value_uk' => $attributeDataUa->ValueName,
                'url' => $attributeUrl,
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

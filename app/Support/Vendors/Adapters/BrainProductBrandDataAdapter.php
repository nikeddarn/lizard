<?php
/**
 * Create vendor product brand data.
 */

namespace App\Support\Vendors\Adapters;


use App\Contracts\Shop\AttributesInterface;
use App\Contracts\Vendor\VendorInterface;
use App\Models\Attribute;
use App\Support\Repositories\BrandRepository;
use App\Support\Vendors\Providers\BrainBrandDataProvider;
use Exception;
use Illuminate\Support\Str;

class BrainProductBrandDataAdapter
{
    /**
     * @var int
     */
    const VENDOR_ID = VendorInterface::BRAIN;
    /**
     * @var BrandRepository
     */
    private $repository;
    /**
     * @var Attribute
     */
    private $attribute;
    /**
     * @var BrainBrandDataProvider
     */
    private $brandDataProvider;


    /**
     * BrainProductBrandDataAdapter constructor.
     * @param BrandRepository $repository
     * @param Attribute $attribute
     * @param BrainBrandDataProvider $brandDataProvider
     */
    public function __construct(BrandRepository $repository, Attribute $attribute, BrainBrandDataProvider $brandDataProvider)
    {
        $this->repository = $repository;
        $this->attribute = $attribute;
        $this->brandDataProvider = $brandDataProvider;
    }

    /**
     * Prepare vendor product brand data.
     *
     * @param $vendorBrandId
     * @return array
     */
    public function prepareVendorProductBrandData($vendorBrandId): array
    {
        // retrieve brand attribute value
        $brandAttributeValue = $this->repository->getBrandValueByVendorId(self::VENDOR_ID, $vendorBrandId);

        // create new brand attribute value
        if (!$brandAttributeValue) {
            try {
                // retrieve brand data
                $vendorBrand = $this->brandDataProvider->getBrandData($vendorBrandId);
            } catch (Exception $exception) {
                return [];
            }

            // prepare product attribute value data
            $vendorBrandValueData = [
                'value_ru' => $vendorBrand->name,
                'value_ua' => $vendorBrand->name,
                'url' => Str::slug($vendorBrand->name),
            ];

            // retrieve brand value by data (prevent insert double)
            $brandAttributeValue = $this->repository->getBrandValueByModelData($vendorBrandValueData);

            if (!$brandAttributeValue) {
                // get brand attribute
                $brandAttribute = Attribute::query()->where('defined_attribute_id', AttributesInterface::BRAND)->first();
                // create new brand value
                $brandAttributeValue = $brandAttribute->attributeValues()->create($vendorBrandValueData);
            }

            // attach brand value to vendor
            $brandAttributeValue->vendors()->attach(self::VENDOR_ID, [
                'vendor_attribute_value_id' => $vendorBrandId,
            ]);
        }

        // prepare brand data
        $brandData = [
            $brandAttributeValue->id => [
                'attributes_id' => $brandAttributeValue->attributes_id,
            ]
        ];

        return $brandData;
    }
}

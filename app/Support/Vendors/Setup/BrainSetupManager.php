<?php
/**
 * Brain setup manager.
 */

namespace App\Support\Vendors\Setup;


use App\Contracts\Vendor\VendorInterface;
use App\Models\Attribute;
use App\Models\VendorStock;
use App\Support\Vendors\Providers\BrainVendorProvider;
use Exception;
use Illuminate\Support\Str;

class BrainSetupManager
{

    const VENDOR_ID = VendorInterface::BRAIN;

    /**
     * @var BrainVendorProvider
     */
    private $vendorProvider;

    /**
     * BrainSetupManager constructor.
     * @param BrainVendorProvider $vendorProvider
     */
    public function __construct(BrainVendorProvider $vendorProvider)
    {
        $this->vendorProvider = $vendorProvider;
    }

    /**
     * Setup vendor data.
     *
     * @throws Exception
     */
    public function setup()
    {
        $this->insertBrands();
        $this->insertStocks();
    }

    /**
     * Insert brands.
     *
     * @throws Exception
     */
    private function insertBrands()
    {
        // create attribute
        $brandAttribute = Attribute::firstOrCreate([
            'name_ru' => 'Бренд',
            'name_ua' => 'Бренд',
        ]);

        // get vendor brands
        $vendorBrands = $this->vendorProvider->getBrands();

        // create array of unique attribute values data keyed by vendor's original attribute value id
        $attributeValuesData = [];
        foreach ($vendorBrands as $vendorBrand) {
            $attributeValuesData[$vendorBrand->vendorID] = [
                'value_ru' => $vendorBrand->name,
                'value_ua' => $vendorBrand->name,
                'url' => Str::slug($vendorBrand->name),
            ];
        }

        // insert attribute values and attach it to vendor
        foreach ($attributeValuesData as $vendorAttributeValueId => $attributeValueData) {
            try {
                // retrieve attribute value
                $attributeValue = $brandAttribute->attributeValues()->where('value_ru', $attributeValueData['value_ru'])->orWhere('url', $attributeValueData['url'])->first();

                // create attribute value
                if (!$attributeValue) {
                    $attributeValue = $brandAttribute->attributeValues()->create($attributeValueData);
                }

                // attach vendor with original attribute value id
                $attributeValue->vendors()->attach(self::VENDOR_ID, [
                    'vendor_attribute_value_id' => $vendorAttributeValueId,
                ]);
            } catch (Exception $exception) {
                throw $exception;
            }

        }
    }

    /**
     * Insert stocks.
     * @throws Exception
     */
    private function insertStocks()
    {
        $vendorStocks = $this->vendorProvider->getStocks();

        foreach ($vendorStocks as $stock){
            VendorStock::firstOrCreate([
                'vendors_id' => self::VENDOR_ID,
                'vendor_stock_id' => $stock->stockID,
                'name_ru' => $stock->name,
                'name_ua' => $stock->name,
            ]);
        }
    }
}
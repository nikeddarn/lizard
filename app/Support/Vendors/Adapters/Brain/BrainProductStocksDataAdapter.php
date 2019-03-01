<?php
/**
 * Create vendor product stocks data.
 */

namespace App\Support\Vendors\Adapters\Brain;


use App\Contracts\Vendor\VendorInterface;
use App\Models\VendorStock;
use App\Support\Vendors\Providers\BrainStockDataProvider;
use Exception;
use Illuminate\Database\Eloquent\Model;
use stdClass;
use Throwable;

class BrainProductStocksDataAdapter
{
    /**
     * @var int
     */
    const VENDOR_ID = VendorInterface::BRAIN;
    /**
     * @var VendorStock
     */
    private $stock;
    /**
     * @var BrainStockDataProvider
     */
    private $stockDataProvider;

    /**
     * BrainProductStocksDataAdapter constructor.
     * @param VendorStock $stock
     * @param BrainStockDataProvider $stockDataProvider
     */
    public function __construct(VendorStock $stock, BrainStockDataProvider $stockDataProvider)
    {
        $this->stock = $stock;
        $this->stockDataProvider = $stockDataProvider;
    }

    /**
     * Prepare vendor product stock data.
     *
     * @param $productDataRu
     * @return array
     */
    public function prepareVendorProductStocksData(stdClass $productDataRu):array
    {
        // stocks available product
        $productStockAvailability = (array)$productDataRu->available;

        // stocks expected product
        $productStockExpected = (array)$productDataRu->stocks_expected;

        // collect stocks data
        $productStockData = [];

        foreach (array_unique(array_merge(array_keys($productStockAvailability), array_keys($productStockExpected))) as $vendorStockId) {
            // get existing vendor stock
            $vendorStock = $this->getVendorStock($vendorStockId);

            // try to create new vendor stock
            if (!$vendorStock) {
                try {
                    $vendorStock = $this->createVendorStock($vendorStockId);
                } catch (Throwable $exception) {
                    continue;
                }
            }

            // create stock data
            $productStockData[$vendorStock->id] = [
                'available' => isset($productStockAvailability[$vendorStockId]) ? $productStockAvailability[$vendorStockId] : 0,
                'available_time' => isset($productStockExpected[$vendorStockId]) ? $productStockExpected[$vendorStockId] : null,
            ];
        }

        return $productStockData;
    }

    /**
     * @param int $vendorStockId
     * @return VendorStock|Model|null
     */
    private function getVendorStock(int $vendorStockId)
    {
        return $this->stock->newQuery()
            ->where([
                ['vendor_stock_id', '=', $vendorStockId],
                ['vendors_id', '=', self::VENDOR_ID],
            ])
            ->first();
    }

    /**
     * @param int $vendorStockId
     * @return VendorStock|Model
     * @throws Exception
     */
    private function createVendorStock(int $vendorStockId): VendorStock
    {
        // receive stock data from vendor
        $vendorStockData = $this->stockDataProvider->getStockData($vendorStockId);

        if (!$vendorStockData){
            throw new Exception('Can not create vendor stock');
        }

        // create new vendor stock
        $stock =  $this->stock->newQuery()->create([
            'vendors_id' => self::VENDOR_ID,
            'vendor_stock_id' => $vendorStockId,
            'name_ru' => $vendorStockData->name,
            'name_uk' => $vendorStockData->name,
        ]);

        return $stock;
    }
}

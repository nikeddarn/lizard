<?php

namespace App\Jobs\Vendors;

use App\Models\VendorProduct;
use App\Support\Vendors\VendorBroker;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class InsertVendorProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 2;
    /**
     * @var VendorProduct
     */
    private $vendorProduct;
    /**
     * @var int
     */
    private $vendorCategoryId;
    /**
     * @var int
     */
    private $localCategoryId;

    /**
     * Create a new job instance.
     *
     * @param VendorProduct|Model $vendorProduct
     * @param int $vendorCategoryId
     * @param int $localCategoryId
     */
    public function __construct(VendorProduct $vendorProduct, int $vendorCategoryId, int $localCategoryId)
    {
        $this->vendorProduct = $vendorProduct;
        $this->vendorCategoryId = $vendorCategoryId;
        $this->localCategoryId = $localCategoryId;
    }

    /**
     * Execute the job.
     *
     * @param VendorBroker $vendorBroker
     * @return void
     * @throws \Exception
     */
    public function handle(VendorBroker $vendorBroker)
    {
        try {
            // insert product via manager
            $vendorBroker->getVendorProductManager($this->vendorProduct->vendors_id)->insertVendorProduct($this->vendorCategoryId, $this->localCategoryId, $this->vendorProduct);
        } catch (Exception $exception) {
            if ($this->attempts() < $this->tries) {
                // reattempt with delay
                $this->release(config('shop.insert_vendor_product.retry') * 60);
            } else {
                // insert job in failed table
                throw $exception;
            }
        }
    }
}

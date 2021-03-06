<?php

namespace App\Jobs\Vendors;

use App\Exceptions\Vendor\DisallowInsertProductException;
use App\Models\SynchronizingProduct;
use App\Support\Vendors\VendorBroker;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class InsertVendorProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;
    /**
     * @var int
     */
    private $vendorCategoriesIds;
    /**
     * @var int
     */
    private $localCategoriesIds;
    /**
     * @var int
     */
    private $vendorId;
    /**
     * @var int
     */
    private $vendorProductId;
    /**
     * @var bool
     */
    private $checkInsertAllow;

    /**
     * Create a new job instance.
     *
     * @param int $vendorId
     * @param array $vendorCategoriesIds
     * @param array $localCategoriesIds
     * @param int $vendorProductId
     * @param bool $checkInsertAllow
     */
    public function __construct(int $vendorId, array $vendorCategoriesIds, array $localCategoriesIds, int $vendorProductId, bool $checkInsertAllow = false)
    {
        $this->vendorId = $vendorId;
        $this->vendorCategoriesIds = $vendorCategoriesIds;
        $this->localCategoriesIds = $localCategoriesIds;
        $this->vendorProductId = $vendorProductId;

        $this->tries = config('vendor.insert_vendor_product.tries');
        $this->checkInsertAllow = $checkInsertAllow;
    }

    /**
     * Execute the job.
     *
     * @param VendorBroker $vendorBroker
     * @return void
     * @throws \Throwable
     */
    public function handle(VendorBroker $vendorBroker)
    {
        if ($this->isJobNotCancelled()) {
            try {
                // insert product via manager
                $vendorBroker->getInsertProductJobManager($this->vendorId)->insertVendorProduct($this->vendorCategoriesIds, $this->localCategoriesIds, $this->vendorProductId, $this->checkInsertAllow);
            } catch (DisallowInsertProductException $exception) {
                // inserting product data don't pass conditions
                // product not inserted
                // do nothing
            } catch (Exception $exception) {
                // log error
                Log::info('Fail insert product: ' . $exception->getMessage());

                // reattempt with delay
                if ($this->attempts() < $this->tries) {
                    $this->release(config('vendor.insert_vendor_product.retry') * 60);
                }

                throw $exception;
            }
        }
    }

    /**
     * Check is job still in synchronizing products ?
     *
     * @return bool
     */
    private function isJobNotCancelled(): bool
    {
        $jobId = $this->job->getJobId();

        return (bool)SynchronizingProduct::query()->where('jobs_id', $jobId)->count();
    }
}

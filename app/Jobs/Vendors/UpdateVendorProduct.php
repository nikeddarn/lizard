<?php

namespace App\Jobs\Vendors;

use App\Models\SynchronizingProduct;
use App\Support\Vendors\VendorBroker;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class UpdateVendorProduct implements ShouldQueue
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
    private $vendorId;
    /**
     * @var int
     */
    private $vendorProductId;

    /**
     * Create a new job instance.
     *
     * @param int $vendorId
     * @param int $vendorProductId
     */
    public function __construct(int $vendorId, int $vendorProductId)
    {
        $this->vendorId = $vendorId;
        $this->vendorProductId = $vendorProductId;
        $this->tries = config('shop.update_vendor_product.tries');
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
                $vendorBroker->getUpdateProductJobManager($this->vendorId)->updateVendorProduct($this->vendorProductId);
            } catch (Exception $exception) {
                // log error
                Log::info('Fail update product price: ' . $exception->getMessage());

                // reattempt with delay
                if ($this->attempts() < $this->tries) {
                    $this->release(config('shop.update_vendor_product.retry') * 60);
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

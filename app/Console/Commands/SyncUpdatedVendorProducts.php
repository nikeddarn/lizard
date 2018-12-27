<?php

namespace App\Console\Commands;

use App\Models\Vendor;
use App\Support\Vendors\VendorBroker;
use Illuminate\Console\Command;

class SyncUpdatedVendorProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch modified vendor products to jobs';

    /**
     * @var VendorBroker
     */
    private $broker;
    /**
     * @var Vendor
     */
    private $vendor;

    /**
     * Create a new command instance.
     *
     * @param Vendor $vendor
     * @param VendorBroker $broker
     */
    public function __construct(Vendor $vendor, VendorBroker $broker)
    {
        parent::__construct();
        $this->broker = $broker;
        $this->vendor = $vendor;
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        // retrieve vendors
        $vendors = $this->vendor->newQuery()->get();

        // sync each vendors
        foreach ($vendors as $vendor) {
            // get manager
            $syncUpdatedProductManager = $this->broker->getSyncUpdatedProductManager($vendor->id);

            // sync product prices
            $vendorSyncAt = $syncUpdatedProductManager->synchronizeUpdatedProducts($vendor->sync_prices_at);

            // update vendor's synchronized_at
            $vendor->sync_prices_at = $vendorSyncAt;
            $vendor->save();
        }
    }
}

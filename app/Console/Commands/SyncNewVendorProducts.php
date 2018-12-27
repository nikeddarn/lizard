<?php

namespace App\Console\Commands;

use App\Models\Vendor;
use App\Support\Vendors\VendorBroker;
use Illuminate\Console\Command;

class SyncNewVendorProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch new vendor products to jobs';

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
            $syncNewProductManager = $this->broker->getSyncNewProductManager($vendor->id);

            // sync product prices
            $vendorSyncAt = $syncNewProductManager->synchronizeNewProducts($vendor->sync_new_products_at);

            // update vendor's synchronized_at
            $vendor->sync_new_products_at = $vendorSyncAt;
            $vendor->save();
        }
    }
}

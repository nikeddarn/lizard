<?php

namespace App\Listeners\Vendor;

class UpdateProductWarranty
{
    /**
     * Handle the event.
     *
     * @param  object $event
     * @return void
     * @throws \Exception
     */
    public function handle($event)
    {
        // get product
        $product = $event->product;

        // get max warranty of vendors
        $maxVendorWarranty = $product->vendorProducts()->max('warranty');

        if ($maxVendorWarranty) {
            $product->warranty = $maxVendorWarranty;
            $product->save();
        }
    }
}
